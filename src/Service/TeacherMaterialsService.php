<?php

namespace App\Service;

use PDO;
use Throwable;

class TeacherMaterialsService
{
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getMaterialsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'topics' => [],
            'materials' => [],
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

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['topics'] = $this->getTeacherTopics($userId);
        $data['materials'] = $this->getTeacherMaterials($userId);

        return $data;
    }

    public function saveMaterial(array $input, array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return $this->error($this->translator->translate('teacher.materials.user_not_authenticated'));
        }

        $materialId = isset($input['id_materiale']) ? (int) $input['id_materiale'] : 0;
        $name = trim((string) ($input['nome_materiale'] ?? ''));
        $description = trim((string) ($input['descrizione_materiale'] ?? ''));
        $topicId = isset($input['id_argomento']) ? (int) $input['id_argomento'] : 0;

        if ($name === '') {
            return $this->error($this->translator->translate('teacher.materials.name.required'));
        }

        if ($topicId <= 0) {
            return $this->error($this->translator->translate('teacher.materials.topic.invalid'));
        }

        $topic = $this->findTopicForTeacher($topicId, $userId);
        if ($topic === null) {
            return $this->error($this->translator->translate('teacher.materials.topic.unavailable'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingMaterial = null;
            if ($materialId > 0) {
                $existingMaterial = $this->findMaterialByIdForTeacher($materialId, $userId);
                if ($existingMaterial === null) {
                    throw new \RuntimeException($this->translator->translate('teacher.materials.not_found'));
                }
            }

            $newFilePath = $this->storeMaterialFile($uploadedFile, (string) ($topic['nome_argomento'] ?? 'argomento'));
            if ($materialId === 0 && $newFilePath === null) {
                throw new \RuntimeException($this->translator->translate('teacher.materials.file.required'));
            }

            if ($materialId === 0) {
                $insert = $pdo->prepare(
                    'INSERT INTO ct_materiali (nome_materiale, descrizione, link_materiale, fk_argomento, fk_utente)
                     VALUES (:nome_materiale, :descrizione, :link_materiale, :fk_argomento, :fk_utente)'
                );

                $insert->execute([
                    'nome_materiale' => $name,
                    'descrizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                    'link_materiale' => (string) $newFilePath,
                    'fk_argomento' => $topicId,
                    'fk_utente' => $userId,
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $this->translator->translate('teacher.materials.created'),
                ];
            }

            $filePathToSave = $newFilePath ?? (string) ($existingMaterial['link_materiale'] ?? '');

            $update = $pdo->prepare(
                'UPDATE ct_materiali
                 SET nome_materiale = :nome_materiale,
                     descrizione = :descrizione,
                     link_materiale = :link_materiale,
                     fk_argomento = :fk_argomento
                 WHERE id_materiale = :id_materiale'
            );

            $update->execute([
                'nome_materiale' => $name,
                'descrizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                'link_materiale' => $filePathToSave,
                'fk_argomento' => $topicId,
                'id_materiale' => $materialId,
            ]);

            $pdo->commit();

            if ($newFilePath !== null) {
                $this->deleteStoredMaterialFile((string) ($existingMaterial['link_materiale'] ?? ''));
            }

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.materials.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deleteMaterial(int $materialId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return $this->error($this->translator->translate('teacher.materials.user_not_authenticated'));
        }

        if ($materialId <= 0) {
            return $this->error($this->translator->translate('teacher.materials.invalid'));
        }

        $material = $this->findMaterialByIdForTeacher($materialId, $userId);
        if ($material === null) {
            return $this->error($this->translator->translate('teacher.materials.not_found'));
        }

        if ($this->isMaterialInUse($materialId)) {
            return $this->error($this->translator->translate('teacher.materials.delete.in_use'));
        }

        $delete = Database::getConnection()->prepare('DELETE FROM ct_materiali WHERE id_materiale = :id_materiale AND fk_utente = :fk_utente');
        $delete->execute([
            'id_materiale' => $materialId,
            'fk_utente' => $userId,
        ]);

        if ($delete->rowCount() < 1) {
            return $this->error($this->translator->translate('teacher.materials.delete.none'));
        }

        $this->deleteStoredMaterialFile((string) ($material['link_materiale'] ?? ''));

        return [
            'success' => true,
            'message' => $this->translator->translate('teacher.materials.deleted'),
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

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function getTeacherTopics(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.id_argomento,
                    a.nome_argomento,
                    m.id_materia,
                    m.nome_materia
             FROM ct_utenti_materie um
             INNER JOIN ct_materie m ON m.id_materia = um.fk_materia
             INNER JOIN ct_argomenti a ON a.fk_materia = m.id_materia
             WHERE um.fk_utente = :fk_utente
             ORDER BY m.nome_materia ASC, a.nome_argomento ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getTeacherMaterials(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_materiale,
                    m.nome_materiale,
                    m.descrizione,
                    m.link_materiale,
                    m.fk_argomento,
                    a.nome_argomento,
                    s.nome_materia
             FROM ct_materiali m
             INNER JOIN ct_argomenti a ON a.id_argomento = m.fk_argomento
             INNER JOIN ct_materie s ON s.id_materia = a.fk_materia
             WHERE m.fk_utente = :fk_utente
             ORDER BY s.nome_materia ASC, a.nome_argomento ASC, m.nome_materiale ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findTopicForTeacher(int $topicId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.id_argomento, a.nome_argomento
             FROM ct_argomenti a
             INNER JOIN ct_utenti_materie um ON um.fk_materia = a.fk_materia
             WHERE a.id_argomento = :id_argomento
               AND um.fk_utente = :fk_utente
             LIMIT 1'
        );
        $stmt->execute([
            'id_argomento' => $topicId,
            'fk_utente' => $userId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function findMaterialByIdForTeacher(int $materialId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_materiali
             WHERE id_materiale = :id_materiale
               AND fk_utente = :fk_utente
             LIMIT 1'
        );
        $stmt->execute([
            'id_materiale' => $materialId,
            'fk_utente' => $userId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function isMaterialInUse(int $materialId): bool
    {
        $direct = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_esercizi WHERE fk_materiale = :fk_materiale');
        $direct->execute(['fk_materiale' => $materialId]);
        if ((int) $direct->fetchColumn() > 0) {
            return true;
        }

        $additional = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_esercizio_materiali WHERE fk_materiale = :fk_materiale');
        $additional->execute(['fk_materiale' => $materialId]);

        return (int) $additional->fetchColumn() > 0;
    }

    private function storeMaterialFile(array $uploadedFile, string $topicName): ?string
    {
        $error = (int) ($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('teacher.materials.upload.error'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('teacher.materials.upload.invalid_file'));
        }

        $originalName = trim((string) ($uploadedFile['name'] ?? 'materiale'));
        $safeOriginalName = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $originalName) ?: 'materiale';

        $topicDirName = $this->slugifyTopicName($topicName);
        $relativeDir = '/assets/materiali/' . $topicDirName;
        $absoluteDir = dirname(__DIR__, 2) . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->translator->translate('teacher.materials.upload.folder_create_failed'));
        }

        $targetName = uniqid('', true) . '_' . $safeOriginalName;
        $destination = $absoluteDir . '/' . $targetName;
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException($this->translator->translate('teacher.materials.upload.save_failed'));
        }

        return $relativeDir . '/' . $targetName;
    }

    private function slugifyTopicName(string $topicName): string
    {
        $topic = trim($topicName);
        if ($topic === '') {
            return 'argomento';
        }

        $normalized = $topic;
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $topic);
            if (is_string($converted) && $converted !== '') {
                $normalized = $converted;
            }
        }

        $normalized = preg_replace('/\s+/', '_', $normalized) ?: $normalized;
        $normalized = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $normalized) ?: $normalized;
        $normalized = trim($normalized, '_');

        return $normalized !== '' ? $normalized : 'argomento';
    }

    private function deleteStoredMaterialFile(string $filePath): void
    {
        $filePath = trim($filePath);
        if ($filePath === '') {
            return;
        }

        $absolutePath = null;

        if (str_starts_with($filePath, '/assets/materiali/')) {
            $absolutePath = dirname(__DIR__, 2) . '/public' . $filePath;
        } elseif (str_starts_with($filePath, './materiali/')) {
            $absolutePath = dirname(__DIR__, 2) . '/legacy/pages/Rewarding/' . ltrim(substr($filePath, 1), '/');
        }

        if ($absolutePath !== null && is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        return match ($permissionStatus) {
            PermissionService::STATUS_OK => null,
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->translator->translate('teacher.materials.permission.session_expired')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->translator->translate('teacher.materials.permission.denied')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->translator->translate('teacher.materials.permission.select_class_first')),
            default => $this->error($this->translator->translate('teacher.materials.permission.not_class_owner')),
        };
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
