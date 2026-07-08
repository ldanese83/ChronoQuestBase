<?php

namespace App\Service;

use PDO;
use Throwable;

class TeacherPowersService
{
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPowersPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'powers' => [],
            'importablePowers' => [],
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

        $data['powers'] = $this->getPowers($classId);
        $data['importablePowers'] = $this->getImportablePowers($classId);

        return $data;
    }

    public function getAssignedPowersPageData(array $filters): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'students' => [],
            'studentPowers' => [],
            'selectedStudentUserId' => 0,
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

        $data['students'] = $this->getStudentsForClass($classId);
        $data['selectedStudentUserId'] = isset($filters['studente']) ? max(0, (int) $filters['studente']) : 0;

        if ($data['selectedStudentUserId'] > 0) {
            $allowedIds = array_map(static fn (array $student): int => (int) $student['id_utente'], $data['students']);
            if (!in_array($data['selectedStudentUserId'], $allowedIds, true)) {
                $data['selectedStudentUserId'] = 0;
            }
        }

        $data['studentPowers'] = $this->getAssignedPowers($classId, $data['selectedStudentUserId']);

        return $data;
    }

    public function savePower(array $input, array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();

        $powerId = isset($input['id_potere']) ? (int) $input['id_potere'] : 0;
        $name = trim((string) ($input['nome_potere'] ?? ''));
        $description = trim((string) ($input['descrizione_potere'] ?? ''));
        $level = isset($input['livello']) ? (int) $input['livello'] : 0;
        $mana = isset($input['mana_pot']) ? (int) $input['mana_pot'] : 0;

        if ($name === '') {
            return $this->error('teacher.powers.name.required');
        }

        if ($description === '') {
            return $this->error('teacher.powers.description.required');
        }

        if ($level < 0) {
            return $this->error('teacher.powers.level.invalid');
        }

        if ($mana < 1) {
            return $this->error('teacher.powers.mana.invalid');
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingPower = null;
            if ($powerId > 0) {
                $existingPower = $this->findPowerById($powerId);
                if ($existingPower === null) {
                    throw new \RuntimeException($this->t('teacher.powers.not_found'));
                }

                if ((int) ($existingPower['fk_classe'] ?? 0) !== $classId) {
                    throw new \RuntimeException($this->t('teacher.powers.edit.class_only'));
                }

                if ((int) ($existingPower['fisso'] ?? 0) === 1) {
                    throw new \RuntimeException($this->t('teacher.powers.fixed.not_editable'));
                }
            }

            $newImagePath = $this->storePowerImage($uploadedFile);
            if ($powerId === 0 && $newImagePath === null) {
                throw new \RuntimeException($this->t('teacher.powers.image.required'));
            }

            if ($powerId === 0) {
                $insert = $pdo->prepare(
                    'INSERT INTO ct_poteri (nome_potere, descrizione_potere, img_potere, livello, mana_necessario, fk_classe, originale)
                     VALUES (:nome_potere, :descrizione_potere, :img_potere, :livello, :mana_necessario, :fk_classe, 0)'
                );

                $insert->execute([
                    'nome_potere' => $name,
                    'descrizione_potere' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                    'img_potere' => (string) $newImagePath,
                    'livello' => $level,
                    'mana_necessario' => $mana,
                    'fk_classe' => $classId,
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => 'teacher.powers.created',
                ];
            }

            $imagePathToSave = $newImagePath ?? (string) ($existingPower['img_potere'] ?? '');

            $update = $pdo->prepare(
                'UPDATE ct_poteri
                 SET nome_potere = :nome_potere,
                     descrizione_potere = :descrizione_potere,
                     img_potere = :img_potere,
                     livello = :livello,
                     mana_necessario = :mana_necessario
                 WHERE id_potere = :id_potere'
            );

            $update->execute([
                'nome_potere' => $name,
                'descrizione_potere' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                'img_potere' => $imagePathToSave,
                'livello' => $level,
                'mana_necessario' => $mana,
                'id_potere' => $powerId,
            ]);

            $pdo->commit();

            if ($newImagePath !== null) {
                $this->deleteStoredPowerImage((string) ($existingPower['img_potere'] ?? ''));
            }

            return [
                'success' => true,
                'message' => 'teacher.powers.updated',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deletePower(int $powerId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        if ($powerId <= 0) {
            return $this->error('teacher.powers.invalid');
        }

        $power = $this->findPowerById($powerId);
        if ($power === null) {
            return $this->error('teacher.powers.not_found');
        }

        if ((int) ($power['fk_classe'] ?? 0) !== $classId) {
            return $this->error('teacher.powers.delete.class_only');
        }

        if ((int) ($power['fisso'] ?? 0) === 1) {
            return $this->error('teacher.powers.fixed.not_deletable');
        }

        $pdo = Database::getConnection();

        $assignmentCheck = $pdo->prepare('SELECT COUNT(*) FROM ct_studenti_poteri WHERE fk_potere = :fk_potere');
        $assignmentCheck->execute(['fk_potere' => $powerId]);
        if ((int) $assignmentCheck->fetchColumn() > 0) {
            return $this->error('teacher.powers.delete.assigned');
        }

        $delete = $pdo->prepare('DELETE FROM ct_poteri WHERE id_potere = :id_potere');
        $delete->execute(['id_potere' => $powerId]);

        if ($delete->rowCount() < 1) {
            return $this->error('teacher.powers.delete.none');
        }

        $this->deleteStoredPowerImage((string) ($power['img_potere'] ?? ''));

        return [
            'success' => true,
            'message' => 'teacher.powers.deleted',
        ];
    }

    public function importPower(int $sourcePowerId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        if ($sourcePowerId <= 0) {
            return $this->error('teacher.powers.import.invalid');
        }

        $sourcePower = $this->findPowerById($sourcePowerId);
        if ($sourcePower === null) {
            return $this->error('teacher.powers.import.source_not_found');
        }

        if ((int) ($sourcePower['fisso'] ?? 0) === 1) {
            return $this->error('teacher.powers.import.fixed_not_allowed');
        }

        if ((int) ($sourcePower['fk_classe'] ?? 0) === $classId) {
            return $this->error('teacher.powers.import.same_class');
        }

        $duplicateCheck = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_poteri
             WHERE fk_classe = :fk_classe
               AND nome_potere = :nome_potere'
        );
        $duplicateCheck->execute([
            'fk_classe' => $classId,
            'nome_potere' => (string) ($sourcePower['nome_potere'] ?? ''),
        ]);

        if ((int) $duplicateCheck->fetchColumn() > 0) {
            return $this->error('teacher.powers.import.duplicate_name');
        }

        $insert = Database::getConnection()->prepare(
            'INSERT INTO ct_poteri (nome_potere, img_potere, descrizione_potere, livello, mana_necessario, fk_classe, originale)
             VALUES (:nome_potere, :img_potere, :descrizione_potere, :livello, :mana_necessario, :fk_classe, 1)'
        );
        $insert->execute([
            'nome_potere' => (string) ($sourcePower['nome_potere'] ?? ''),
            'img_potere' => (string) ($sourcePower['img_potere'] ?? ''),
            'descrizione_potere' => (string) ($sourcePower['descrizione_potere'] ?? ''),
            'livello' => (int) ($sourcePower['livello'] ?? 0),
            'mana_necessario' => (int) ($sourcePower['mana_necessario'] ?? 0),
            'fk_classe' => $classId,
        ]);

        return [
            'success' => true,
            'message' => 'teacher.powers.import.success',
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

    private function getPowers(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_potere,
                    p.nome_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'nome_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                    p.descrizione_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'descrizione_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                    p.img_potere,
                    p.livello,
                    p.mana_necessario,
                    p.fisso,
                    p.fk_classe,
                    c.nome_classe AS classe_origine
             FROM ct_poteri p
             LEFT JOIN ct_classi c ON c.id_classe = p.fk_classe
             WHERE p.fk_classe = :fk_classe OR p.fisso = 1
             ORDER BY p.livello ASC, p.nome_potere ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getImportablePowers(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_potere,
                    p.nome_potere,
                    p.descrizione_potere,
                    p.img_potere,
                    p.livello,
                    p.mana_necessario,
                    c.nome_classe,
                    a.anno_scolastico
             FROM ct_poteri p
             INNER JOIN ct_classi c ON c.id_classe = p.fk_classe
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE p.fisso = 0
               AND COALESCE(p.originale, 0) = 0
               AND p.fk_classe <> :fk_classe
               AND NOT EXISTS (
                    SELECT 1
                    FROM ct_poteri p2
                    WHERE p2.fk_classe = :fk_classe_dup
                      AND p2.nome_potere = p.nome_potere
               )
             ORDER BY c.nome_classe ASC, p.nome_potere ASC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_classe_dup' => $classId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getStudentsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.id_utente,
                    u.nome,
                    u.cognome,
                    s.id_studente
             FROM ct_studenti_classi sc
             INNER JOIN ct_studenti s ON s.id_studente = sc.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE sc.fk_classe = :fk_classe
             ORDER BY u.cognome ASC, u.nome ASC'
        );

        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAssignedPowers(int $classId, int $studentUserId): array
    {
        $sql = 'SELECT sp.id_stud_pot,
                       p.id_potere,
                       p.nome_potere,
                       (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'nome_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                       p.descrizione_potere,
                       (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'descrizione_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                       p.img_potere,
                       p.livello,
                       p.mana_necessario,
                       u.id_utente,
                       u.nome,
                       u.cognome
                FROM ct_studenti_poteri sp
                INNER JOIN ct_poteri p ON p.id_potere = sp.fk_potere
                INNER JOIN ct_studenti s ON s.id_studente = sp.fk_studente
                INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
                INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
                WHERE sc.fk_classe = :fk_classe';

        $params = ['fk_classe' => $classId];

        if ($studentUserId > 0) {
            $sql .= ' AND u.id_utente = :id_utente';
            $params['id_utente'] = $studentUserId;
        }

        $sql .= ' ORDER BY u.cognome ASC, u.nome ASC, p.livello ASC, p.nome_potere ASC';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findPowerById(int $powerId): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM ct_poteri WHERE id_potere = :id_potere LIMIT 1');
        $stmt->execute(['id_potere' => $powerId]);

        $power = $stmt->fetch(PDO::FETCH_ASSOC);

        return $power ?: null;
    }

    private function storePowerImage(array $uploadedFile): ?string
    {
        if (!isset($uploadedFile['error']) || (int) $uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->t('teacher.powers.image.upload_error'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->t('teacher.powers.image.invalid_file'));
        }

        $mimeType = mime_content_type($tmpName) ?: '';
        $allowedMimes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimes[$mimeType])) {
            throw new \RuntimeException($this->t('teacher.powers.image.unsupported_format'));
        }

        $extension = $allowedMimes[$mimeType];
        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo((string) ($uploadedFile['name'] ?? 'potere'), PATHINFO_FILENAME));
        $safeBaseName = trim((string) $safeBaseName, '_');
        if ($safeBaseName === '') {
            $safeBaseName = 'potere';
        }

        $fileName = uniqid('power_', true) . '_' . $safeBaseName . '.' . $extension;

        $projectRoot = dirname(__DIR__, 2);
        $relativeDir = '/assets/images/Poteri';
        $absoluteDir = $projectRoot . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->t('teacher.powers.image.create_folder_failed'));
        }

        $destination = $absoluteDir . '/' . $fileName;
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException($this->t('teacher.powers.image.save_failed'));
        }

        return $relativeDir . '/' . $fileName;
    }

    private function deleteStoredPowerImage(string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, '/assets/images/Poteri/')) {
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
            return $this->error('teacher.powers.permission_denied');
        }

        return null;
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            throw new \RuntimeException($this->t('teacher.powers.class.not_selected'));
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
