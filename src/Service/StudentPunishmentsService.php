<?php

namespace App\Service;

use PDO;
use Throwable;

class StudentPunishmentsService
{
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPunishmentsPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $studentId = (int) ($base['student']['id_studente'] ?? 0);
        $base['punishments'] = $this->getAssignedPunishments($studentId);

        return $base;
    }

    public function uploadPunishmentDelivery(array $input, array $uploadedFile): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error('student.punishments.upload.permission_denied');
        }

        $studentId = (int) ($base['student']['id_studente'] ?? 0);
        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $punishmentId = (int) ($input['id_punizione'] ?? 0);

        if ($punishmentId <= 0) {
            return $this->error('student.punishments.invalid');
        }

        $assignment = $this->getStudentPunishmentAssignment($studentId, $punishmentId);
        if ($assignment === null) {
            return $this->error('student.punishments.not_assigned');
        }

        if ((int) ($assignment['completata'] ?? 0) === 1) {
            return $this->error('student.punishments.already_submitted');
        }

        if (!isset($uploadedFile['error']) || (int) $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            return $this->error('student.punishments.upload.failed');
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error('student.punishments.upload.invalid_file');
        }

        $safeOriginalName = preg_replace('/[^a-zA-Z0-9._-]/', '_', (string) ($uploadedFile['name'] ?? 'consegna'));
        $safeOriginalName = trim((string) $safeOriginalName, '_');
        if ($safeOriginalName === '') {
            $safeOriginalName = 'consegna';
        }

        $fileName = sprintf(
            's%d_p%d_%s_%s',
            $studentId,
            $punishmentId,
            date('Ymd_His'),
            $safeOriginalName
        );

        $projectRoot = dirname(__DIR__, 2);
        $relativeDir = '/assets/uploads/punizioni';
        $absoluteDir = $projectRoot . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            return $this->error('student.punishments.upload.create_folder_failed');
        }

        $relativePath = $relativeDir . '/' . $fileName;
        $absolutePath = $absoluteDir . '/' . $fileName;

        if (!move_uploaded_file($tmpName, $absolutePath)) {
            return $this->error('student.punishments.upload.save_failed');
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'UPDATE ct_studenti_punizioni
                 SET completata = 1,
                     link_consegna = :link_consegna
                 WHERE fk_studente = :fk_studente
                   AND fk_punizione = :fk_punizione'
            );

            $stmt->execute([
                'link_consegna' => $relativePath,
                'fk_studente' => $studentId,
                'fk_punizione' => $punishmentId,
            ]);

            $studentFullName = trim(((string) ($base['student']['nome'] ?? '')) . ' ' . ((string) ($base['student']['cognome'] ?? '')));
            $alertText = sprintf($this->t('student.punishments.alert.uploaded_material'), $studentFullName);

            $alertStmt = $pdo->prepare(
                'INSERT INTO ct_alerts (fk_classe, data_alert, letto, testo, tipologia, link, doc_stud, fk_studente)
                 VALUES (:fk_classe, :data_alert, 0, :testo, :tipologia, :link, 0, 0)'
            );

            $alertStmt->execute([
                'fk_classe' => $classId,
                'data_alert' => date('Y-m-d'),
                'testo' => $alertText,
                'tipologia' => 'Punizioni',
                'link' => '/docenti/punizioni/assegnate',
            ]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->t('student.punishments.upload.success'),
            ];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if (is_file($absolutePath)) {
                @unlink($absolutePath);
            }

            return $this->error('student.punishments.upload.save_error');
        }
    }

    private function baseProtectedPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
            'punishments' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();
        if ($classId === null || $userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, c.colore, c.icona, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
               AND c.eliminata = 0
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);
        $classroom = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($classroom === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente,
                    u.nome,
                    u.cognome,
                    u.username
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE u.id_utente = :id_utente
               AND sc.fk_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);

        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($student === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $data['classroom'] = $classroom;
        $data['student'] = $student;

        return $data;
    }

    private function getAssignedPunishments(int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT sp.fk_punizione,
                    sp.completata,
                    sp.data_scadenza,
                    sp.link_consegna,
                    p.descrizione_punizione,
                    p.img_punizione
             FROM ct_studenti_punizioni sp
             INNER JOIN ct_punizioni p ON p.id_punizione = sp.fk_punizione
             WHERE sp.fk_studente = :fk_studente
               AND sp.completata = 0
             ORDER BY sp.data_scadenza ASC, sp.id_stud_pun DESC'
        );
        $stmt->execute(['fk_studente' => $studentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getStudentPunishmentAssignment(int $studentId, int $punishmentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_stud_pun, completata
             FROM ct_studenti_punizioni
             WHERE fk_studente = :fk_studente
               AND fk_punizione = :fk_punizione
             LIMIT 1'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_punizione' => $punishmentId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $this->t($message),
        ];
    }

    private function t(string $key): string
    {
        return $this->translator->translate($key);
    }
}
