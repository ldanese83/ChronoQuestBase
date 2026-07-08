<?php

namespace App\Service;

use PDO;
use Throwable;
use ZipArchive;

class TeacherCharacterService
{

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getCharactersPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'characters' => [],
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

        $data['characters'] = $this->getCharactersForClass($classId);

        return $data;
    }

    public function saveCharacter(array $input, array $files): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $characterId = isset($input['id_personaggio']) ? (int) $input['id_personaggio'] : 0;
        $name = trim((string) ($input['nome_personaggio'] ?? ''));
        $life = max(1, (int) ($input['vita_iniziale'] ?? 1));
        $mana = max(1, (int) ($input['mana_iniziale'] ?? 1));
        $description = trim((string) ($input['descrizione'] ?? ''));
        $color = trim((string) ($input['color'] ?? '#808080')) ?: '#808080';
        $borderColor = trim((string) ($input['bordercolor'] ?? '#efefef')) ?: '#efefef';

        if ($name === '') {
            return $this->error($this->translator->translate('teacher.characters.name.required'));
        }

        $avatarFile = is_array($files['immagine'] ?? null) ? $files['immagine'] : [];
        $avatarNoBgFile = is_array($files['img_senza_sfondo'] ?? null) ? $files['img_senza_sfondo'] : [];

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingCharacter = null;
            if ($characterId > 0) {
                $existingCharacter = $this->findCharacterById($classId, $characterId);
                if ($existingCharacter === null) {
                    throw new \RuntimeException($this->translator->translate('teacher.characters.not_found_in_class'));
                }
            }

            $avatarPath = $this->resolveUploadPath(
                $avatarFile,
                '/assets/images/Personaggi',
                $characterId === 0 ? null : (string) ($existingCharacter['immagine'] ?? '')
            );
            $avatarNoBgPath = $this->resolveUploadPath(
                $avatarNoBgFile,
                '/assets/images/Personaggi',
                $characterId === 0 ? null : (string) ($existingCharacter['img_senza_sfondo'] ?? '')
            );

            if ($characterId === 0) {
                if ($avatarPath === null) {
                    throw new \RuntimeException($this->translator->translate('teacher.characters.avatar.required'));
                }

                $pdo->prepare(
                    'INSERT INTO ct_personaggi (uuid, nome_personaggio, immagine, vita_iniziale, descrizione, color, bordercolor, mana_iniziale, fk_classe, img_senza_sfondo, originale)
                     VALUES (:uuid, :nome_personaggio, :immagine, :vita_iniziale, :descrizione, :color, :bordercolor, :mana_iniziale, :fk_classe, :img_senza_sfondo, 1)'
                )->execute([
                    'uuid' => $this->generateUuidV4(),
                    'nome_personaggio' => $name,
                    'immagine' => $avatarPath,
                    'vita_iniziale' => $life,
                    'descrizione' => $description,
                    'color' => $color,
                    'bordercolor' => $borderColor,
                    'mana_iniziale' => $mana,
                    'fk_classe' => $classId,
                    'img_senza_sfondo' => (string) ($avatarNoBgPath ?? ''),
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $this->translator->translate('teacher.characters.created'),
                ];
            }

            $params = [
                'id_personaggio' => $characterId,
                'nome_personaggio' => $name,
                'vita_iniziale' => $life,
                'descrizione' => $description,
                'color' => $color,
                'bordercolor' => $borderColor,
                'mana_iniziale' => $mana,
            ];

            $sql = 'UPDATE ct_personaggi
                    SET nome_personaggio = :nome_personaggio,
                        vita_iniziale = :vita_iniziale,
                        descrizione = :descrizione,
                        color = :color,
                        bordercolor = :bordercolor,
                        mana_iniziale = :mana_iniziale';

            if ($avatarPath !== null) {
                $sql .= ', immagine = :immagine';
                $params['immagine'] = $avatarPath;
            }

            if ($avatarNoBgPath !== null) {
                $sql .= ', img_senza_sfondo = :img_senza_sfondo';
                $params['img_senza_sfondo'] = $avatarNoBgPath;
            }

            $sql .= ' WHERE id_personaggio = :id_personaggio';

            $pdo->prepare($sql)->execute($params);
            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.characters.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function getImportExportMenuPageData(): array
    {
        $data = $this->getCharactersPageData();

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['originalCharactersInClass'] = [];
            return $data;
        }

        $classId = (int) ((new PermissionService())->getCurrentClassId() ?? 0);
        $data['originalCharactersInClass'] = $this->getOriginalCharactersForClass($classId);

        return $data;
    }

    public function getExternalOriginalCharactersPageData(): array
    {
        $data = $this->getCharactersPageData();

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['availableExternalOriginalCharacters'] = [];
            return $data;
        }

        $classId = (int) ((new PermissionService())->getCurrentClassId() ?? 0);
        $data['availableExternalOriginalCharacters'] = $this->getImportableOriginalCharactersFromOtherClasses($classId);

        return $data;
    }

    public function importOriginalCharacterFromAnotherClass(int $characterId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($characterId <= 0) {
            return $this->error($this->translator->translate('teacher.characters.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $sourceCharacter = $this->findImportableOriginalCharacterById($classId, $characterId);
        if ($sourceCharacter === null) {
            return $this->error($this->translator->translate('teacher.characters.not_importable'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $newCharacterId = $this->duplicateCharacterTree($pdo, $characterId, $classId);
            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.characters.import.success'),
                'newCharacterId' => $newCharacterId,
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function buildCharacterExportArchive(int $characterId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $character = $this->findCharacterById($classId, $characterId);
        if ($character === null) {
            return $this->error($this->translator->translate('teacher.characters.not_found_in_class'));
        }

        $this->ensureCharacterTreeUuids($characterId);

        $payload = $this->buildCharacterExportPayload($characterId, $classId);

        $tmpDir = sys_get_temp_dir() . '/chronoquest_character_export_' . uniqid('', true);
        if (!mkdir($tmpDir, 0775, true) && !is_dir($tmpDir)) {
            return $this->error($this->translator->translate('teacher.characters.export.temp_dir_failed'));
        }

        file_put_contents($tmpDir . '/personaggio.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        foreach ($this->collectCharacterAssetPaths($payload) as $assetPath) {
            $absoluteSource = $this->resolveAbsoluteAssetPath($assetPath);
            if ($absoluteSource === null || !is_file($absoluteSource)) {
                continue;
            }

            $relative = ltrim($assetPath, '/');
            $target = $tmpDir . '/' . $relative;
            $targetDir = dirname($target);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                continue;
            }
            copy($absoluteSource, $target);
        }

        $safeName = preg_replace('/[^a-z0-9_-]+/i', '_', (string) ($character['nome_personaggio'] ?? 'personaggio'));
        $zipPath = sys_get_temp_dir() . '/chronoquest_personaggio_' . $safeName . '_' . date('Ymd_His') . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->deleteDirectory($tmpDir);
            return $this->error($this->translator->translate('teacher.characters.export.zip_create_failed'));
        }

        $this->addDirectoryToZip($zip, $tmpDir, $tmpDir);
        $zip->close();
        $this->deleteDirectory($tmpDir);

        return [
            'success' => true,
            'absolutePath' => $zipPath,
            'fileName' => basename($zipPath),
        ];
    }

    public function importCharacterFromArchive(array $files): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $archive = is_array($files['character_archive'] ?? null) ? $files['character_archive'] : [];
        if (($archive['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->translator->translate('teacher.characters.import.archive.required'));
        }

        $tmpName = (string) ($archive['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->translator->translate('teacher.characters.import.invalid_zip_file'));
        }

        $extractDir = sys_get_temp_dir() . '/chronoquest_character_import_' . uniqid('', true);
        if (!mkdir($extractDir, 0775, true) && !is_dir($extractDir)) {
            return $this->error($this->translator->translate('teacher.characters.import.prepare_failed'));
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.characters.import.open_zip_failed'));
        }

        $zip->extractTo($extractDir);
        $zip->close();

        $jsonPath = $extractDir . '/personaggio.json';
        if (!is_file($jsonPath)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.characters.import.character_json_missing'));
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        if (!is_array($payload)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.characters.import.invalid_json'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $this->restoreAssetsFromArchive($extractDir);
            $newCharacterId = $this->importCharacterPayload($pdo, $payload, $classId);
            $pdo->commit();

            $this->deleteDirectory($extractDir);

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.characters.import.file.success'),
                'newCharacterId' => $newCharacterId,
            ];
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

    private function getCharactersForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_personaggio, uuid, nome_personaggio, immagine, vita_iniziale, mana_iniziale, descrizione, color, bordercolor, img_senza_sfondo
             FROM ct_personaggi
             WHERE fk_classe = :fk_classe
             ORDER BY nome_personaggio ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findCharacterById(int $classId, int $characterId): ?array
    {
        if ($classId <= 0 || $characterId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_personaggio, uuid, nome_personaggio, immagine, vita_iniziale, mana_iniziale, descrizione, color, bordercolor, img_senza_sfondo, fk_classe
             FROM ct_personaggi
             WHERE id_personaggio = :id_personaggio
               AND fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_personaggio' => $characterId,
            'fk_classe' => $classId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function resolveUploadPath(array $file, string $publicDirectory, ?string $existingPath): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $existingPath;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.upload.error'));
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.upload.invalid_file'));
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true)) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.upload.unsupported_format'));
        }

        $targetDir = dirname(__DIR__, 2) . '/public' . $publicDirectory;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.upload.folder_create_failed'));
        }

        $targetName = uniqid('character_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $targetName;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.upload.save_failed'));
        }

        return $publicDirectory . '/' . $targetName;
    }

    private function getOriginalCharactersForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_personaggio, uuid, nome_personaggio, immagine, vita_iniziale, mana_iniziale
             FROM ct_personaggi
             WHERE fk_classe = :fk_classe
               AND COALESCE(originale, 1) = 1
             ORDER BY nome_personaggio ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getImportableOriginalCharactersFromOtherClasses(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_personaggio, p.uuid, p.nome_personaggio, p.immagine, p.vita_iniziale, p.mana_iniziale
             FROM ct_personaggi p
             WHERE COALESCE(p.originale, 0) = 1
               AND p.fk_classe <> :fk_classe
               AND NOT EXISTS (
                    SELECT 1
                    FROM ct_personaggi cp
                    WHERE cp.fk_classe = :fk_classe
                      AND cp.nome_personaggio = p.nome_personaggio
               )
             ORDER BY p.nome_personaggio ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findImportableOriginalCharacterById(int $classId, int $characterId): ?array
    {
        foreach ($this->getImportableOriginalCharactersFromOtherClasses($classId) as $character) {
            if ((int) ($character['id_personaggio'] ?? 0) === $characterId) {
                return $character;
            }
        }

        return null;
    }

    private function duplicateCharacterTree(PDO $pdo, int $sourceCharacterId, int $targetClassId): int
    {
        $source = $pdo->prepare('SELECT * FROM ct_personaggi WHERE id_personaggio = :id_personaggio LIMIT 1');
        $source->execute(['id_personaggio' => $sourceCharacterId]);
        $character = $source->fetch(PDO::FETCH_ASSOC);
        if ($character === false) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.not_found_c'));
        }

        $pdo->prepare(
            'INSERT INTO ct_personaggi (nome_personaggio, immagine, vita_iniziale, descrizione, color, bordercolor, mana_iniziale, fk_classe, img_senza_sfondo, originale)
             VALUES (:nome_personaggio, :immagine, :vita_iniziale, :descrizione, :color, :bordercolor, :mana_iniziale, :fk_classe, :img_senza_sfondo, 0)'
        )->execute([
            'nome_personaggio' => (string) ($character['nome_personaggio'] ?? ''),
            'immagine' => (string) ($character['immagine'] ?? ''),
            'vita_iniziale' => (int) ($character['vita_iniziale'] ?? 1),
            'descrizione' => (string) ($character['descrizione'] ?? ''),
            'color' => (string) ($character['color'] ?? '#808080'),
            'bordercolor' => (string) ($character['bordercolor'] ?? '#efefef'),
            'mana_iniziale' => (int) ($character['mana_iniziale'] ?? 1),
            'fk_classe' => $targetClassId,
            'img_senza_sfondo' => (string) ($character['img_senza_sfondo'] ?? ''),
        ]);

        $newCharacterId = (int) $pdo->lastInsertId();

        $customizations = $pdo->prepare('SELECT * FROM ct_personalizzazioni WHERE fk_personaggio = :fk_personaggio');
        $customizations->execute(['fk_personaggio' => $sourceCharacterId]);
        foreach ($customizations->fetchAll(PDO::FETCH_ASSOC) ?: [] as $customization) {
            $pdo->prepare(
                'INSERT INTO ct_personalizzazioni (uuid, nome_personalizzazione, img, tipo, costo, fk_personaggio, fk_classe, fk_studente, approvata, descrizione, suffisso_costume)
                 VALUES (:uuid, :nome_personalizzazione, :img, :tipo, :costo, :fk_personaggio, :fk_classe, NULL, 1, :descrizione, :suffisso_costume)'
            )->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_personalizzazione' => (string) ($customization['nome_personalizzazione'] ?? ''),
                'img' => (string) ($customization['img'] ?? ''),
                'tipo' => (string) ($customization['tipo'] ?? ''),
                'costo' => (int) ($customization['costo'] ?? 0),
                'fk_personaggio' => $newCharacterId,
                'fk_classe' => $targetClassId,
                'descrizione' => (string) ($customization['descrizione'] ?? ''),
                'suffisso_costume' => (string) ($customization['suffisso_costume'] ?? ''),
            ]);
        }

        return $newCharacterId;
    }

    private function ensureCharacterTreeUuids(int $characterId): void
    {
        $pdo = Database::getConnection();
        $pdo->prepare('UPDATE ct_personaggi SET uuid = :uuid WHERE id_personaggio = :id_personaggio AND (uuid IS NULL OR uuid = "")')
            ->execute([
                'uuid' => $this->generateUuidV4(),
                'id_personaggio' => $characterId,
            ]);

        $stmt = $pdo->prepare('SELECT id_personalizzazione FROM ct_personalizzazioni WHERE fk_personaggio = :fk_personaggio AND (uuid IS NULL OR uuid = "")');
        $stmt->execute(['fk_personaggio' => $characterId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
            $pdo->prepare('UPDATE ct_personalizzazioni SET uuid = :uuid WHERE id_personalizzazione = :id_personalizzazione')
                ->execute([
                    'uuid' => $this->generateUuidV4(),
                    'id_personalizzazione' => (int) ($row['id_personalizzazione'] ?? 0),
                ]);
        }
    }

    private function buildCharacterExportPayload(int $characterId, int $classId): array
    {
        $characterStmt = Database::getConnection()->prepare(
            'SELECT id_personaggio, uuid, nome_personaggio, immagine, vita_iniziale, mana_iniziale, descrizione, color, bordercolor, img_senza_sfondo
             FROM ct_personaggi
             WHERE id_personaggio = :id_personaggio
             LIMIT 1'
        );
        $characterStmt->execute(['id_personaggio' => $characterId]);
        $character = $characterStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $customizationStmt = Database::getConnection()->prepare(
            'SELECT id_personalizzazione, uuid, nome_personalizzazione, img, tipo, costo, descrizione, suffisso_costume
             FROM ct_personalizzazioni
             WHERE fk_personaggio = :fk_personaggio
             ORDER BY id_personalizzazione ASC'
        );
        $customizationStmt->execute(['fk_personaggio' => $characterId]);

        return [
            'exported_at' => date('c'),
            'class_id' => $classId,
            'character' => $character,
            'customizations' => $customizationStmt->fetchAll(PDO::FETCH_ASSOC) ?: [],
        ];
    }

    private function collectCharacterAssetPaths(array $payload): array
    {
        $paths = [];

        $character = (array) ($payload['character'] ?? []);
        $paths[] = (string) ($character['immagine'] ?? '');
        $paths[] = (string) ($character['img_senza_sfondo'] ?? '');

        foreach ((array) ($payload['customizations'] ?? []) as $customization) {
            $paths[] = (string) ($customization['img'] ?? '');
        }

        $normalized = [];
        foreach (array_unique($paths) as $path) {
            $path = trim((string) $path);
            if ($path === '' || preg_match('/^https?:\/\//i', $path) === 1 || !str_contains($path, 'assets/')) {
                continue;
            }
            $normalized[] = str_starts_with($path, '/') ? $path : '/' . ltrim($path, '/');
        }

        return $normalized;
    }

    private function addDirectoryToZip(ZipArchive $zip, string $directory, string $basePath): void
    {
        foreach (array_diff(scandir($directory) ?: [], ['.', '..']) as $item) {
            $path = $directory . '/' . $item;
            if (is_dir($path)) {
                $this->addDirectoryToZip($zip, $path, $basePath);
                continue;
            }

            $localName = ltrim(str_replace($basePath, '', $path), '/');
            $zip->addFile($path, $localName);
        }
    }

    private function restoreAssetsFromArchive(string $extractDir): void
    {
        $source = $extractDir . '/assets/images';
        if (!is_dir($source)) {
            return;
        }

        $destination = dirname(__DIR__, 2) . '/public/assets/images';
        $this->copyDirectory($source, $destination, true);
    }

    private function copyDirectory(string $source, string $destination, bool $skipCostumes = false): void
    {
        if (!is_dir($source)) {
            return;
        }

        if (!is_dir($destination) && !mkdir($destination, 0775, true) && !is_dir($destination)) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.import.asset_folder_create_failed'));
        }

        foreach (array_diff(scandir($source) ?: [], ['.', '..']) as $entry) {
            $sourcePath = $source . '/' . $entry;
            $destinationPath = $destination . '/' . $entry;

            if ($skipCostumes && $this->isCostumeArchivePath($sourcePath)) {
                continue;
            }

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destinationPath, $skipCostumes);
                continue;
            }

            $destinationDir = dirname($destinationPath);
            if (!is_dir($destinationDir) && !mkdir($destinationDir, 0775, true) && !is_dir($destinationDir)) {
                throw new \RuntimeException($this->translator->translate('teacher.characters.import.asset_destination_failed'));
            }

            copy($sourcePath, $destinationPath);
        }
    }

    private function isCostumeArchivePath(string $path): bool
    {
        return str_contains(str_replace('\\', '/', $path), '/Personalizzazioni/Costumes');
    }

    private function importCharacterPayload(PDO $pdo, array $payload, int $classId): int
    {
        $character = (array) ($payload['character'] ?? []);

        $pdo->prepare(
            'INSERT INTO ct_personaggi (nome_personaggio, immagine, vita_iniziale, descrizione, color, bordercolor, mana_iniziale, fk_classe, img_senza_sfondo, originale)
             VALUES ( :nome_personaggio, :immagine, :vita_iniziale, :descrizione, :color, :bordercolor, :mana_iniziale, :fk_classe, :img_senza_sfondo, 0)'
        )->execute([
            'nome_personaggio' => (string) ($character['nome_personaggio'] ?? 'Personaggio importato'),
            'immagine' => $this->normalizeAssetPathForDb((string) ($character['immagine'] ?? '')),
            'vita_iniziale' => (int) ($character['vita_iniziale'] ?? 1),
            'descrizione' => (string) ($character['descrizione'] ?? ''),
            'color' => (string) ($character['color'] ?? '#808080'),
            'bordercolor' => (string) ($character['bordercolor'] ?? '#efefef'),
            'mana_iniziale' => (int) ($character['mana_iniziale'] ?? 1),
            'fk_classe' => $classId,
            'img_senza_sfondo' => $this->normalizeAssetPathForDb((string) ($character['img_senza_sfondo'] ?? '')),
        ]);

        $newCharacterId = (int) $pdo->lastInsertId();

        foreach ((array) ($payload['customizations'] ?? []) as $customization) {
            $pdo->prepare(
                'INSERT INTO ct_personalizzazioni (uuid, nome_personalizzazione, img, tipo, costo, fk_personaggio, fk_classe, fk_studente, approvata, descrizione, suffisso_costume)
                 VALUES (:uuid, :nome_personalizzazione, :img, :tipo, :costo, :fk_personaggio, :fk_classe, NULL, 1, :descrizione, :suffisso_costume)'
            )->execute([
                'uuid' => (string) (($customization['uuid'] ?? '') ?: $this->generateUuidV4()),
                'nome_personalizzazione' => (string) ($customization['nome_personalizzazione'] ?? ''),
                'img' => $this->normalizeAssetPathForDb((string) ($customization['img'] ?? '')),
                'tipo' => (string) ($customization['tipo'] ?? ''),
                'costo' => (int) ($customization['costo'] ?? 0),
                'fk_personaggio' => $newCharacterId,
                'fk_classe' => $classId,
                'descrizione' => (string) ($customization['descrizione'] ?? ''),
                'suffisso_costume' => (string) ($customization['suffisso_costume'] ?? ''),
            ]);
        }

        return $newCharacterId;
    }

    private function normalizeAssetPathForDb(string $path): string
    {
        $path = trim($path);
        if ($path === '' || !str_contains($path, 'assets/')) {
            return $path;
        }

        return str_starts_with($path, '/') ? $path : '/' . ltrim($path, '/');
    }

    private function resolveAbsoluteAssetPath(string $path): ?string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//i', $path) === 1) {
            return null;
        }

        return dirname(__DIR__, 2) . '/public/' . ltrim($path, '/');
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (array_diff(scandir($dir) ?: [], ['.', '..']) as $item) {
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $status = $permissionService->checkPermissionsTeacher();

        if ($status === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($status) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->translator->translate('teacher.characters.permission.session_expired')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->translator->translate('teacher.characters.permission.denied')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->translator->translate('teacher.characters.permission.select_class_first')),
            default => $this->error($this->translator->translate('teacher.characters.permission.not_class_owner')),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();

        if ($classId === null) {
            throw new \RuntimeException($this->translator->translate('teacher.characters.class.not_selected'));
        }

        return $classId;
    }

    private function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
