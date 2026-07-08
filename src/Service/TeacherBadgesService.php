<?php

namespace App\Service;

use PDO;
use Throwable;
use ZipArchive;

class TeacherBadgesService
{

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }


    public function getBadgesPageData(array $filters): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'subjects' => [],
            'topics' => [],
            'badges' => [],
            'selectedSubjectId' => 0,
            'selectedTopicId' => 0,
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

        $data['subjects'] = $this->getTeacherSubjects($userId);
        $data['selectedSubjectId'] = isset($filters['materia']) ? max(0, (int) $filters['materia']) : 0;
        if ($data['selectedSubjectId'] <= 0 && $data['subjects'] !== []) {
            $data['selectedSubjectId'] = (int) $data['subjects'][0]['id_materia'];
        }

        if ($data['selectedSubjectId'] > 0) {
            $data['topics'] = $this->getTopicsBySubjectId($data['selectedSubjectId']);
        }

        $data['selectedTopicId'] = isset($filters['argomento']) ? max(0, (int) $filters['argomento']) : 0;
        if ($data['selectedTopicId'] > 0 && !$this->topicBelongsToSubject($data['selectedTopicId'], $data['selectedSubjectId'])) {
            $data['selectedTopicId'] = 0;
        }

        $data['badges'] = $this->getBadges($data['selectedSubjectId'], $data['selectedTopicId']);

        return $data;
    }

    public function getAssignedBadgesPageData(array $filters): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'students' => [],
            'studentBadges' => [],
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

        $data['studentBadges'] = $this->getAssignedBadges($classId, $data['selectedStudentUserId']);

        return $data;
    }

    public function saveBadge(array $input, array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return $this->error($this->translator->translate('teacher.badges.user_not_authenticated'));
        }

        $badgeId = isset($input['id_badge']) ? (int) $input['id_badge'] : 0;
        $name = trim((string) ($input['nome_badge'] ?? ''));
        $description = trim((string) ($input['descrizione_badge'] ?? ''));
        $topicId = isset($input['id_argomento']) ? (int) $input['id_argomento'] : 0;
        $minimumAverage = isset($input['media_minima']) ? (float) $input['media_minima'] : 0.0;
        $exerciseCount = isset($input['num_esercizi']) ? (int) $input['num_esercizi'] : 0;
        $gender = strtoupper(trim((string) ($input['sesso'] ?? 'U')));

        if ($name === '') {
            return $this->error($this->translator->translate('teacher.badges.name.required'));
        }

        if ($description === '') {
            return $this->error($this->translator->translate('teacher.badges.description.required'));
        }

        if ($topicId <= 0 || !$this->topicExists($topicId)) {
            return $this->error($this->translator->translate('teacher.badges.topic.invalid'));
        }

        if ($minimumAverage < 0 || $minimumAverage > 10) {
            return $this->error($this->translator->translate('teacher.badges.minimum_average.invalid'));
        }

        if ($exerciseCount <= 0) {
            return $this->error($this->translator->translate('teacher.badges.exercise_count.invalid'));
        }

        if (!in_array($gender, ['M', 'F', 'U'], true)) {
            $gender = 'U';
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingBadge = null;
            if ($badgeId > 0) {
                $existingBadge = $this->findBadgeById($badgeId);
                if ($existingBadge === null) {
                    throw new \RuntimeException($this->translator->translate('teacher.badges.not_found'));
                }

                if ((int) ($existingBadge['fk_utente_creatore'] ?? 0) !== $userId) {
                    throw new \RuntimeException($this->translator->translate('teacher.badges.edit.own_only'));
                }
            }

            $newImagePath = $this->storeBadgeImage($uploadedFile);
            if ($badgeId === 0 && $newImagePath === null) {
                throw new \RuntimeException($this->translator->translate('teacher.badges.image.required'));
            }

            if ($badgeId === 0) {
                $insert = $pdo->prepare(
                    'INSERT INTO ct_badge (nome_badge, descrizione, img_badge, fk_argomento, media_minima, num_esercizi, fk_utente_creatore, sesso)
                     VALUES (:nome_badge, :descrizione, :img_badge, :fk_argomento, :media_minima, :num_esercizi, :fk_utente_creatore, :sesso)'
                );

                $insert->execute([
                    'nome_badge' => $name,
                    'descrizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                    'img_badge' => (string) $newImagePath,
                    'fk_argomento' => $topicId,
                    'media_minima' => $minimumAverage,
                    'num_esercizi' => $exerciseCount,
                    'fk_utente_creatore' => $userId,
                    'sesso' => $gender,
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $this->translator->translate('teacher.badges.created'),
                ];
            }

            $imagePathToSave = $newImagePath ?? (string) ($existingBadge['img_badge'] ?? '');

            $update = $pdo->prepare(
                'UPDATE ct_badge
                 SET nome_badge = :nome_badge,
                     descrizione = :descrizione,
                     img_badge = :img_badge,
                     fk_argomento = :fk_argomento,
                     media_minima = :media_minima,
                     num_esercizi = :num_esercizi,
                     sesso = :sesso
                 WHERE id_badge = :id_badge'
            );

            $update->execute([
                'nome_badge' => $name,
                'descrizione' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                'img_badge' => $imagePathToSave,
                'fk_argomento' => $topicId,
                'media_minima' => $minimumAverage,
                'num_esercizi' => $exerciseCount,
                'sesso' => $gender,
                'id_badge' => $badgeId,
            ]);

            $pdo->commit();

            if ($newImagePath !== null) {
                $this->deleteStoredBadgeImage((string) ($existingBadge['img_badge'] ?? ''));
            }

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.badges.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deleteBadge(int $badgeId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($badgeId <= 0) {
            return $this->error($this->translator->translate('teacher.badges.invalid'));
        }

        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return $this->error($this->translator->translate('teacher.badges.user_not_authenticated'));
        }

        $badge = $this->findBadgeById($badgeId);
        if ($badge === null) {
            return $this->error($this->translator->translate('teacher.badges.not_found'));
        }

        if ((int) ($badge['fk_utente_creatore'] ?? 0) !== $userId) {
            return $this->error($this->translator->translate('teacher.badges.delete.own_only'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $deleteAssignments = $pdo->prepare('DELETE FROM ct_badge_alunni WHERE fk_badge = :fk_badge');
            $deleteAssignments->execute(['fk_badge' => $badgeId]);

            $deleteBadge = $pdo->prepare('DELETE FROM ct_badge WHERE id_badge = :id_badge');
            $deleteBadge->execute(['id_badge' => $badgeId]);

            $pdo->commit();

            $this->deleteStoredBadgeImage((string) ($badge['img_badge'] ?? ''));

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.badges.deleted'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function topicsBySubject(int $subjectId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($subjectId <= 0) {
            return [
                'success' => true,
                'topics' => [],
            ];
        }

        return [
            'success' => true,
            'topics' => $this->getTopicsBySubjectId($subjectId),
        ];
    }

    public function buildBadgesExportArchive(int $subjectId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($subjectId <= 0) {
            return $this->error($this->translator->translate('teacher.badges.export.subject_required'));
        }

        $permissionService = new PermissionService();
        $userId = (int) ($permissionService->getCurrentUserId() ?? 0);
        if ($userId <= 0) {
            return $this->error($this->translator->translate('teacher.badges.user_not_authenticated'));
        }

        $allowedSubjectIds = array_map(static fn (array $subject): int => (int) ($subject['id_materia'] ?? 0), $this->getTeacherSubjects($userId));
        if (!in_array($subjectId, $allowedSubjectIds, true)) {
            return $this->error($this->translator->translate('teacher.badges.export.subject_unavailable'));
        }

        $subject = $this->findSubjectById($subjectId);
        if ($subject === null) {
            return $this->error($this->translator->translate('teacher.badges.subject.not_found'));
        }

        $payload = $this->buildBadgesExportPayload($subjectId);
        if (($payload['badges'] ?? []) === []) {
            return $this->error($this->translator->translate('teacher.badges.export.empty'));
        }

        $tmpDir = sys_get_temp_dir() . '/chronoquest_badges_export_' . uniqid('', true);
        $assetsDir = $tmpDir . '/assets';
        if (!mkdir($assetsDir, 0775, true) && !is_dir($assetsDir)) {
            return $this->error($this->translator->translate('teacher.badges.export.temp_dir_failed'));
        }

        file_put_contents($tmpDir . '/badges.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $copiedFiles = $this->copyBadgeAssetsForExport($payload, $assetsDir);

        $zipPath = sys_get_temp_dir() . '/chronoquest_badges_' . preg_replace('/[^a-z0-9_-]+/i', '_', (string) ($subject['nome_materia'] ?? 'materia')) . '_' . date('Ymd_His') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->deleteDirectory($tmpDir);
            return $this->error($this->translator->translate('teacher.badges.export.zip_create_failed'));
        }

        $zip->addFile($tmpDir . '/badges.json', 'badges.json');
        foreach ($copiedFiles as $relative => $absolute) {
            $zip->addFile($absolute, 'assets/' . $relative);
        }
        $zip->close();
        $this->deleteDirectory($tmpDir);

        return ['success' => true, 'absolutePath' => $zipPath, 'fileName' => basename($zipPath)];
    }

    public function importBadgesFromArchive(array $files, array $topicResolution = []): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $archive = is_array($files['badge_archive'] ?? null) ? $files['badge_archive'] : [];
        if (($archive['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->translator->translate('teacher.badges.import.invalid_archive'));
        }
        $tmpName = (string) ($archive['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->translator->translate('teacher.badges.import.invalid_zip_file'));
        }

        $extractDir = sys_get_temp_dir() . '/chronoquest_badges_import_' . uniqid('', true);
        if (!mkdir($extractDir, 0775, true) && !is_dir($extractDir)) {
            return $this->error($this->translator->translate('teacher.badges.import.prepare_failed'));
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            return $this->error($this->translator->translate('teacher.badges.import.open_zip_failed'));
        }
        $zip->extractTo($extractDir);
        $zip->close();

        $jsonPath = $extractDir . '/badges.json';
        if (!is_file($jsonPath)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.badges.import.badges_json_missing'));
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        if (!is_array($payload)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.badges.import.invalid_json'));
        }

        $pdo = Database::getConnection();
        $permissionService = new PermissionService();
        $currentUserId = (int) ($permissionService->getCurrentUserId() ?? 0);
        if ($currentUserId <= 0) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.badges.user_not_authenticated'));
        }

        try {
            $pdo->beginTransaction();
            $importedCount = $this->importBadgesPayload($pdo, $payload, $extractDir, $topicResolution, $currentUserId);
            $pdo->commit();
            $this->deleteDirectory($extractDir);
            return ['success' => true, 'message' => $this->translator->translate('teacher.badges.import.success'), 'imported_count' => $importedCount];
        } catch (\RuntimeException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deleteDirectory($extractDir);
            $resolutionPayload = json_decode($exception->getMessage(), true);
            if (is_array($resolutionPayload)) {
                return $resolutionPayload;
            }
            return $this->error($this->translator->translate('teacher.badges.import.topic_resolution_required'));
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deleteDirectory($extractDir);
            return $this->error($exception->getMessage());
        }
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

        return $row ?: null;
    }

    private function getTeacherSubjects(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT DISTINCT m.id_materia, m.nome_materia
             FROM ct_materie m
             INNER JOIN ct_utenti_materie um ON um.fk_materia = m.id_materia
             WHERE um.fk_utente = :fk_utente
             ORDER BY m.nome_materia ASC'
        );

        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findSubjectById(int $subjectId): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT id_materia, nome_materia FROM ct_materie WHERE id_materia = :id_materia LIMIT 1');
        $stmt->execute(['id_materia' => $subjectId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getTopicsBySubjectId(int $subjectId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_argomento, nome_argomento, fk_materia
             FROM ct_argomenti
             WHERE fk_materia = :fk_materia
             ORDER BY nome_argomento ASC'
        );
        $stmt->execute(['fk_materia' => $subjectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function topicBelongsToSubject(int $topicId, int $subjectId): bool
    {
        if ($topicId <= 0 || $subjectId <= 0) {
            return false;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_argomenti
             WHERE id_argomento = :id_argomento
               AND fk_materia = :fk_materia'
        );
        $stmt->execute([
            'id_argomento' => $topicId,
            'fk_materia' => $subjectId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function topicExists(int $topicId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_argomenti
             WHERE id_argomento = :id_argomento'
        );
        $stmt->execute(['id_argomento' => $topicId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function getBadges(int $subjectId, int $topicId): array
    {
        $sql = 'SELECT b.id_badge,
                       b.nome_badge,
                       b.descrizione,
                       b.img_badge,
                       b.num_esercizi,
                       b.media_minima,
                       b.fk_utente_creatore,
                       b.fk_argomento,
                       b.sesso,
                       a.nome_argomento,
                       m.id_materia,
                       m.nome_materia,
                       u.nome AS creatore_nome,
                       u.cognome AS creatore_cognome
                FROM ct_badge b
                INNER JOIN ct_argomenti a ON a.id_argomento = b.fk_argomento
                INNER JOIN ct_materie m ON m.id_materia = a.fk_materia
                LEFT JOIN ct_utenti u ON u.id_utente = b.fk_utente_creatore
                WHERE 1=1';

        $params = [];

        if ($subjectId > 0) {
            $sql .= ' AND m.id_materia = :id_materia';
            $params['id_materia'] = $subjectId;
        }

        if ($topicId > 0) {
            $sql .= ' AND a.id_argomento = :id_argomento';
            $params['id_argomento'] = $topicId;
        }

        $sql .= ' ORDER BY b.nome_badge ASC';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findBadgeById(int $badgeId): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM ct_badge WHERE id_badge = :id_badge LIMIT 1');
        $stmt->execute(['id_badge' => $badgeId]);

        $badge = $stmt->fetch(PDO::FETCH_ASSOC);

        return $badge ?: null;
    }

    private function buildBadgesExportPayload(int $subjectId): array
    {
        $subject = $this->findSubjectById($subjectId);

        $stmt = Database::getConnection()->prepare(
            'SELECT b.id_badge,
                    b.nome_badge,
                    b.descrizione,
                    b.img_badge,
                    b.num_esercizi,
                    b.media_minima,
                    b.sesso,
                    b.fk_utente_creatore,
                    a.uuid AS argomento_uuid,
                    a.nome_argomento
             FROM ct_badge b
             INNER JOIN ct_argomenti a ON a.id_argomento = b.fk_argomento
             WHERE a.fk_materia = :fk_materia
             ORDER BY b.nome_badge ASC'
        );
        $stmt->execute(['fk_materia' => $subjectId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $badges = array_map(static function (array $row): array {
            return [
                'nome_badge' => (string) ($row['nome_badge'] ?? ''),
                'descrizione' => (string) ($row['descrizione'] ?? ''),
                'img_badge' => (string) ($row['img_badge'] ?? ''),
                'num_esercizi' => (int) ($row['num_esercizi'] ?? 0),
                'media_minima' => (float) ($row['media_minima'] ?? 0),
                'sesso' => (string) ($row['sesso'] ?? 'U'),
                'fk_utente_creatore' => (int) ($row['fk_utente_creatore'] ?? 0),
                'argomento' => [
                    'uuid' => (string) ($row['argomento_uuid'] ?? ''),
                    'nome' => (string) ($row['nome_argomento'] ?? ''),
                ],
            ];
        }, $rows);

        return [
            'exported_at' => date('c'),
            'subject' => [
                'id_materia' => (int) ($subject['id_materia'] ?? 0),
                'nome_materia' => (string) ($subject['nome_materia'] ?? ''),
            ],
            'badges' => $badges,
        ];
    }

    private function copyBadgeAssetsForExport(array $payload, string $assetsDir): array
    {
        $copied = [];
        foreach ((array) ($payload['badges'] ?? []) as $badge) {
            $path = (string) ($badge['img_badge'] ?? '');
            $absolute = $this->resolveAbsoluteBadgeAssetPath($path);
            if ($absolute === null || !is_file($absolute)) {
                continue;
            }
            $name = uniqid('badge_asset_', true) . '_' . basename($absolute);
            $target = $assetsDir . '/' . $name;
            copy($absolute, $target);
            $copied[$name] = $target;
        }

        return $copied;
    }

    private function importBadgesPayload(PDO $pdo, array $payload, string $extractDir, array $topicResolution, int $creatorUserId): int
    {
        $badges = (array) ($payload['badges'] ?? []);
        if ($badges === []) {
            throw new \RuntimeException('Nessun badge presente nell\'archivio.');
        }

        $missingTopics = [];
        foreach ($badges as &$badge) {
            $badge['resolved_fk_argomento'] = $this->resolveTopicIdForImportedBadge($pdo, (array) $badge, $topicResolution, $missingTopics);
        }
        unset($badge);

        if ($missingTopics !== []) {
            $availableTopics = $pdo->query(
                'SELECT a.id_argomento, a.nome_argomento, a.uuid, a.fk_materia, m.nome_materia
                 FROM ct_argomenti a
                 INNER JOIN ct_materie m ON m.id_materia = a.fk_materia
                 ORDER BY m.nome_materia ASC, a.nome_argomento ASC'
            )->fetchAll(PDO::FETCH_ASSOC) ?: [];

            throw new \RuntimeException(json_encode([
                'success' => false,
                'requires_topic_resolution' => true,
                'message' => 'Alcuni argomenti non sono presenti nel database. Seleziona dove importare i badge.',
                'missing_topics' => array_values($missingTopics),
                'available_topics' => $availableTopics,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $insert = $pdo->prepare(
            'INSERT INTO ct_badge (nome_badge, img_badge, descrizione, fk_utente_creatore, fk_argomento, num_esercizi, media_minima, sesso)
             VALUES (:nome_badge, :img_badge, :descrizione, :fk_utente_creatore, :fk_argomento, :num_esercizi, :media_minima, :sesso)'
        );

        $importedCount = 0;
        foreach ($badges as $badge) {
            $insert->execute([
                'nome_badge' => (string) ($badge['nome_badge'] ?? 'Badge importato'),
                'img_badge' => $this->importBadgeAssetPath((string) ($badge['img_badge'] ?? ''), $extractDir),
                'descrizione' => (string) ($badge['descrizione'] ?? ''),
                'fk_utente_creatore' => $creatorUserId,
                'fk_argomento' => (int) ($badge['resolved_fk_argomento'] ?? 0),
                'num_esercizi' => max(1, (int) ($badge['num_esercizi'] ?? 1)),
                'media_minima' => max(0.0, min(10.0, (float) ($badge['media_minima'] ?? 0))),
                'sesso' => in_array((string) ($badge['sesso'] ?? 'U'), ['M', 'F', 'U'], true) ? (string) $badge['sesso'] : 'U',
            ]);
            $importedCount++;
        }

        return $importedCount;
    }

    private function resolveTopicIdForImportedBadge(PDO $pdo, array $badge, array $topicResolution, array &$missingTopics): int
    {
        $topic = (array) ($badge['argomento'] ?? []);
        $topicUuid = trim((string) ($topic['uuid'] ?? ''));
        $topicName = trim((string) ($topic['nome'] ?? ''));
        $topicKey = $topicUuid !== '' ? ('uuid:' . $topicUuid) : ('name:' . mb_strtolower($topicName));

        if ($topicUuid !== '') {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE uuid = :uuid LIMIT 1');
            $stmt->execute(['uuid' => $topicUuid]);
            $found = (int) ($stmt->fetchColumn() ?: 0);
            if ($found > 0) {
                return $found;
            }
        }

        if ($topicName !== '') {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE nome_argomento = :nome_argomento LIMIT 1');
            $stmt->execute(['nome_argomento' => $topicName]);
            $found = (int) ($stmt->fetchColumn() ?: 0);
            if ($found > 0) {
                return $found;
            }
        }

        $mappedTopicId = (int) ($topicResolution[$topicKey]['topic_id'] ?? 0);
        if ($mappedTopicId > 0) {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE id_argomento = :id_argomento LIMIT 1');
            $stmt->execute(['id_argomento' => $mappedTopicId]);
            $found = (int) ($stmt->fetchColumn() ?: 0);
            if ($found > 0) {
                return $found;
            }
        }

        $missingTopics[$topicKey] = [
            'key' => $topicKey,
            'uuid' => $topicUuid,
            'nome' => $topicName !== '' ? $topicName : 'Argomento senza nome',
        ];

        return 0;
    }

    private function resolveAbsoluteBadgeAssetPath(string $path): ?string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//i', $path) === 1 || !str_starts_with($path, '/assets/images/Badge/')) {
            return null;
        }

        return dirname(__DIR__, 2) . '/public' . $path;
    }

    private function importBadgeAssetPath(string $path, string $extractDir): string
    {
        $path = trim($path);
        if ($path === '' || !str_starts_with($path, '/assets/images/Badge/')) {
            return $path;
        }

        $sourceFileName = basename($path);
        $sourcePath = null;
        $preferredSource = $extractDir . '/assets/' . $sourceFileName;
        if (is_file($preferredSource)) {
            $sourcePath = $preferredSource;
        } else {
            $matches = glob($extractDir . '/assets/*_' . $sourceFileName) ?: [];
            if ($matches !== []) {
                $sourcePath = (string) $matches[0];
            }
        }

        if ($sourcePath === null || !is_file($sourcePath)) {
            return $path;
        }

        $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Badge';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return $path;
        }

        $targetName = uniqid('imported_badge_', true) . '_' . basename($sourcePath);
        copy($sourcePath, $targetDir . '/' . $targetName);

        return '/assets/images/Badge/' . $targetName;
    }

    private function deleteDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $directory . '/' . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
                continue;
            }
            @unlink($path);
        }

        @rmdir($directory);
    }

    private function storeBadgeImage(array $uploadedFile): ?string
    {
        if (!isset($uploadedFile['error']) || (int) $uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Errore durante il caricamento dell\'immagine.');
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException('File immagine non valido.');
        }

        $mimeType = mime_content_type($tmpName) ?: '';
        $allowedMimes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimes[$mimeType])) {
            throw new \RuntimeException('Formato immagine non supportato. Usa PNG, JPG, GIF o WEBP.');
        }

        $extension = $allowedMimes[$mimeType];
        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo((string) ($uploadedFile['name'] ?? 'badge'), PATHINFO_FILENAME));
        $safeBaseName = trim((string) $safeBaseName, '_');
        if ($safeBaseName === '') {
            $safeBaseName = 'badge';
        }

        $fileName = uniqid('badge_', true) . '_' . $safeBaseName . '.' . $extension;

        $projectRoot = dirname(__DIR__, 2);
        $relativeDir = '/assets/images/Badge';
        $absoluteDir = $projectRoot . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException('Impossibile creare la cartella immagini dei badge.');
        }

        $destination = $absoluteDir . '/' . $fileName;
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException('Impossibile salvare l\'immagine del badge.');
        }

        return $relativeDir . '/' . $fileName;
    }

    private function deleteStoredBadgeImage(string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, '/assets/images/Badge/')) {
            return;
        }

        $absolutePath = dirname(__DIR__, 2) . '/public' . $imagePath;
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
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

    private function getAssignedBadges(int $classId, int $studentUserId): array
    {
        $sql = 'SELECT ba.id_badge_alunno,
                       ba.data_conquista,
                       b.id_badge,
                       b.nome_badge,
                       b.descrizione,
                       b.img_badge,
                       u.id_utente,
                       u.nome,
                       u.cognome,
                       a.nome_argomento,
                       m.nome_materia
                FROM ct_badge_alunni ba
                INNER JOIN ct_badge b ON b.id_badge = ba.fk_badge
                INNER JOIN ct_utenti u ON u.id_utente = ba.fk_utente
                INNER JOIN ct_studenti s ON s.fk_utente = u.id_utente
                INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
                INNER JOIN ct_argomenti a ON a.id_argomento = b.fk_argomento
                INNER JOIN ct_materie m ON m.id_materia = a.fk_materia
                WHERE sc.fk_classe = :fk_classe';

        $params = ['fk_classe' => $classId];

        if ($studentUserId > 0) {
            $sql .= ' AND u.id_utente = :id_utente';
            $params['id_utente'] = $studentUserId;
        }

        $sql .= ' ORDER BY u.cognome ASC, u.nome ASC, ba.data_conquista DESC, b.nome_badge ASC';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionStatus = (new PermissionService())->checkPermissionsTeacher();

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $this->error('Non hai i permessi necessari per eseguire questa operazione.');
        }

        return null;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
