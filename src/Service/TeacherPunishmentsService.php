<?php

namespace App\Service;

use PDO;
use Throwable;

class TeacherPunishmentsService
{
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPunishmentsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'punishments' => [],
            'importablePunishments' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['punishments'] = $this->getPunishments($classId);
        $data['importablePunishments'] = $this->getImportablePunishments($classId);

        return $data;
    }

    public function getAssignedPunishmentsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'assignedPunishments' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['assignedPunishments'] = $this->getAssignedPunishments($classId);

        return $data;
    }

    public function savePunishment(array $input, array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();

        $punishmentId = isset($input['id_punizione']) ? (int) $input['id_punizione'] : 0;
        $description = trim((string) ($input['descrizione_punizione'] ?? ''));
        $days = isset($input['giorni_per_consegna']) ? (int) $input['giorni_per_consegna'] : 0;

        if ($description === '') {
            return $this->error($this->t('teacher.punishments.description.required'));
        }

        if ($days < 1) {
            return $this->error($this->t('teacher.punishments.days.invalid'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingPunishment = null;
            if ($punishmentId > 0) {
                $existingPunishment = $this->findPunishmentById($punishmentId);
                if ($existingPunishment === null) {
                    throw new \RuntimeException($this->t('teacher.punishments.not_found'));
                }

                if ((int) ($existingPunishment['fk_classe'] ?? 0) !== $classId) {
                    throw new \RuntimeException($this->t('teacher.punishments.edit.class_only'));
                }
            }

            $newImagePath = $this->storePunishmentImage($uploadedFile);
            if ($punishmentId === 0 && $newImagePath === null) {
                throw new \RuntimeException($this->t('teacher.punishments.image.required'));
            }

            if ($punishmentId === 0) {
                $insert = $pdo->prepare(
                    'INSERT INTO ct_punizioni (descrizione_punizione, img_punizione, giorni_per_consegna, fk_classe)
                     VALUES (:descrizione_punizione, :img_punizione, :giorni_per_consegna, :fk_classe)'
                );

                $insert->execute([
                    'descrizione_punizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                    'img_punizione' => (string) $newImagePath,
                    'giorni_per_consegna' => $days,
                    'fk_classe' => $classId,
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $this->t('teacher.punishments.created'),
                ];
            }

            $imagePathToSave = $newImagePath ?? (string) ($existingPunishment['img_punizione'] ?? '');
            $update = $pdo->prepare(
                'UPDATE ct_punizioni
                 SET descrizione_punizione = :descrizione_punizione,
                     img_punizione = :img_punizione,
                     giorni_per_consegna = :giorni_per_consegna
                 WHERE id_punizione = :id_punizione'
            );

            $update->execute([
                'descrizione_punizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                'img_punizione' => $imagePathToSave,
                'giorni_per_consegna' => $days,
                'id_punizione' => $punishmentId,
            ]);

            $pdo->commit();

            if ($newImagePath !== null) {
                $this->deleteStoredPunishmentImage((string) ($existingPunishment['img_punizione'] ?? ''));
            }

            return [
                'success' => true,
                'message' => $this->t('teacher.punishments.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deletePunishment(int $punishmentId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        if ($punishmentId <= 0) {
            return $this->error($this->t('teacher.punishments.invalid'));
        }

        $punishment = $this->findPunishmentById($punishmentId);
        if ($punishment === null) {
            return $this->error($this->t('teacher.punishments.not_found'));
        }

        if ((int) ($punishment['fk_classe'] ?? 0) !== $classId) {
            return $this->error($this->t('teacher.punishments.delete.class_only'));
        }

        $assignmentCheck = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_studenti_punizioni WHERE fk_punizione = :fk_punizione');
        $assignmentCheck->execute(['fk_punizione' => $punishmentId]);
        if ((int) $assignmentCheck->fetchColumn() > 0) {
            return $this->error($this->t('teacher.punishments.delete.assigned'));
        }

        $delete = Database::getConnection()->prepare('DELETE FROM ct_punizioni WHERE id_punizione = :id_punizione');
        $delete->execute(['id_punizione' => $punishmentId]);

        if ($delete->rowCount() < 1) {
            return $this->error($this->t('teacher.punishments.delete.none'));
        }

        $this->deleteStoredPunishmentImage((string) ($punishment['img_punizione'] ?? ''));

        return [
            'success' => true,
            'message' => $this->t('teacher.punishments.deleted'),
        ];
    }

    public function importPunishment(int $sourcePunishmentId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        if ($sourcePunishmentId <= 0) {
            return $this->error($this->t('teacher.punishments.import.invalid'));
        }

        $sourcePunishment = $this->findPunishmentById($sourcePunishmentId);
        if ($sourcePunishment === null) {
            return $this->error($this->t('teacher.punishments.import.source_not_found'));
        }

        if ((int) ($sourcePunishment['fk_classe'] ?? 0) === $classId) {
            return $this->error($this->t('teacher.punishments.import.same_class'));
        }

        $duplicateCheck = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_punizioni
             WHERE fk_classe = :fk_classe
               AND descrizione_punizione = :descrizione_punizione'
        );
        $duplicateCheck->execute([
            'fk_classe' => $classId,
            'descrizione_punizione' => (string) ($sourcePunishment['descrizione_punizione'] ?? ''),
        ]);

        if ((int) $duplicateCheck->fetchColumn() > 0) {
            return $this->error($this->t('teacher.punishments.import.duplicate_description'));
        }

        $insert = Database::getConnection()->prepare(
            'INSERT INTO ct_punizioni (descrizione_punizione, img_punizione, giorni_per_consegna, fk_classe)
             VALUES (:descrizione_punizione, :img_punizione, :giorni_per_consegna, :fk_classe)'
        );
        $insert->execute([
            'descrizione_punizione' => (string) ($sourcePunishment['descrizione_punizione'] ?? ''),
            'img_punizione' => (string) ($sourcePunishment['img_punizione'] ?? ''),
            'giorni_per_consegna' => (int) ($sourcePunishment['giorni_per_consegna'] ?? 1),
            'fk_classe' => $classId,
        ]);

        return [
            'success' => true,
            'message' => $this->t('teacher.punishments.import.success'),
        ];
    }

    private function getClassroom(int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        $classroom = $stmt->fetch(PDO::FETCH_ASSOC);

        return $classroom ?: null;
    }

    private function getPunishments(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_punizione,
                    descrizione_punizione,
                    img_punizione,
                    giorni_per_consegna
             FROM ct_punizioni
             WHERE fk_classe = :fk_classe
             ORDER BY id_punizione DESC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getImportablePunishments(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT MIN(p.id_punizione) AS id_punizione,
                    p.descrizione_punizione,
                    p.img_punizione,
                    p.giorni_per_consegna,
                    c.nome_classe,
                    a.anno_scolastico
             FROM ct_punizioni p
             INNER JOIN ct_classi c ON c.id_classe = p.fk_classe
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE p.fk_classe <> :fk_classe
               AND NOT EXISTS (
                    SELECT 1
                    FROM ct_punizioni p2
                    WHERE p2.fk_classe = :fk_classe_dup
                      AND p2.descrizione_punizione = p.descrizione_punizione
               )
             GROUP BY p.descrizione_punizione, p.img_punizione, p.giorni_per_consegna, c.nome_classe, a.anno_scolastico
             ORDER BY c.nome_classe ASC, p.descrizione_punizione ASC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_classe_dup' => $classId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAssignedPunishments(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT sp.id_stud_pun,
                    u.nome,
                    u.cognome,
                    p.descrizione_punizione,
                    p.giorni_per_consegna,
                    sp.data_scadenza,
                    sp.link_consegna
             FROM ct_studenti_punizioni sp
             INNER JOIN ct_punizioni p ON p.id_punizione = sp.fk_punizione
             INNER JOIN ct_studenti s ON s.id_studente = sp.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :fk_classe
             ORDER BY sp.data_scadenza DESC, u.cognome ASC, u.nome ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findPunishmentById(int $punishmentId): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM ct_punizioni WHERE id_punizione = :id_punizione LIMIT 1');
        $stmt->execute(['id_punizione' => $punishmentId]);

        $punishment = $stmt->fetch(PDO::FETCH_ASSOC);

        return $punishment ?: null;
    }

    private function storePunishmentImage(array $uploadedFile): ?string
    {
        if (!isset($uploadedFile['error']) || (int) $uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->t('teacher.punishments.image.upload_error'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->t('teacher.punishments.image.invalid_file'));
        }

        $mimeType = mime_content_type($tmpName) ?: '';
        $allowedMimes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimes[$mimeType])) {
            throw new \RuntimeException($this->t('teacher.punishments.image.unsupported_format'));
        }

        $extension = $allowedMimes[$mimeType];
        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo((string) ($uploadedFile['name'] ?? 'punizione'), PATHINFO_FILENAME));
        $safeBaseName = trim((string) $safeBaseName, '_');
        if ($safeBaseName === '') {
            $safeBaseName = 'punizione';
        }

        $fileName = uniqid('punishment_', true) . '_' . $safeBaseName . '.' . $extension;

        $projectRoot = dirname(__DIR__, 2);
        $relativeDir = '/assets/images/Punizioni';
        $absoluteDir = $projectRoot . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->t('teacher.punishments.image.create_folder_failed'));
        }

        $destination = $absoluteDir . '/' . $fileName;
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException($this->t('teacher.punishments.image.save_failed'));
        }

        return $relativeDir . '/' . $fileName;
    }

    private function deleteStoredPunishmentImage(string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, '/assets/images/Punizioni/')) {
            return;
        }

        $absolutePath = dirname(__DIR__, 2) . '/public' . $imagePath;
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionStatus = (new PermissionService())->checkPermissionsTeacher();

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.punishments.permission_denied'));
        }

        return null;
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            throw new \RuntimeException($this->t('teacher.punishments.class.not_selected'));
        }

        return $classId;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    private function t(string $key): string
    {
        return $this->translator->translate($key);
    }
}
