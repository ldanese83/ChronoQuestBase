<?php

namespace App\Service;

use PDO;
use Throwable;
use ZipArchive;

class TeacherCustomizationService
{
    private const FILTER_TYPES = ['Sfondo', 'BigBackground', 'Capelli', 'Personale', 'Equipaggiamento', 'Pet'];
    private const SET_TYPES = ['Equipaggiamento', 'Pet', 'BigBackground', 'Sfondo'];
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPersonalizationsPageData(): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['personalizations'] = [];
            $data['characters'] = [];
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $data['personalizations'] = $this->getPersonalizationsForClass($classId);
        $data['characters'] = $this->getCharactersForClass($classId);
        $data['filterTypes'] = self::FILTER_TYPES;
        $data['equipmentAbilities'] = $this->getAbilitiesByType(false);
        $data['petAbilities'] = $this->getAbilitiesByType(true);
        $data['personalizationAbilities'] = $this->getPersonalizationAbilities($classId);

        return $data;
    }

    public function savePersonalization(array $input, array $files): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $id = (int) ($input['id_personalizzazione'] ?? 0);
        $type = trim((string) ($input['tipo'] ?? ''));
        $name = trim((string) ($input['nome_personalizzazione'] ?? ''));
        $cost = max(0, (int) ($input['costo'] ?? 0));
        $characterId = (int) ($input['fk_personaggio'] ?? 0);
        $description = trim((string) ($input['descrizione'] ?? ''));
        $suffix = trim((string) ($input['suffisso_costume'] ?? ''));
        $abilities = $this->sanitizeAbilities($input['abilities'] ?? null);

        if ($name === '' || !in_array($type, self::FILTER_TYPES, true)) {
            return $this->error($this->t('teacher.customizations.name_type.required'));
        }

        $pdo = Database::getConnection();
        $existing = null;
        if ($id > 0) {
            $existing = $this->findEditablePersonalization($classId, $id);
            if ($existing === null) {
                return $this->error($this->t('teacher.customizations.not_found_or_not_editable'));
            }
        }

        $imageFile = is_array($files['img'] ?? null) ? $files['img'] : [];
        $imagePath = "";
        if($type==="Capelli") {
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/Capelli', $existing['img'] ?? null,false);
        }else if($type==="Sfondo") {
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/Sfondi', $existing['img'] ?? null,false);
        }else if($type==="BigBackground") {
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/BigBackground', $existing['img'] ?? null,false);
        }else if($type==="Pet"){
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/Pet', $existing['img'] ?? null,false);
        }else if($type==="Equipaggiamento") {
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/Equip', $existing['img'] ?? null,false);
        }else if($type==="Personale"){
            $imagePath = $this->resolveUploadPath($imageFile, '/assets/images/Personalizzazioni/Studenti', $existing['img'] ?? null,false);
        }
        
        if ($id === 0 && $imagePath === null) {
            return $this->error($this->t('teacher.customizations.image.required'));
        }

        $fkPersonaggio = in_array($type, ['Capelli', 'Equipaggiamento'], true) ? max(0, $characterId) : 0;

        if ($id === 0) {
            $pdo->prepare(
                'INSERT INTO ct_personalizzazioni
                    (uuid, nome_personalizzazione, img, tipo, costo, fk_personaggio, fk_classe, fk_studente, approvata, descrizione, suffisso_costume)
                 VALUES
                    (:uuid, :nome_personalizzazione, :img, :tipo, :costo, :fk_personaggio, :fk_classe, NULL, 1, :descrizione, :suffisso_costume)'
            )->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_personalizzazione' => $name,
                'img' => (string) $imagePath,
                'tipo' => $type,
                'costo' => $cost,
                'fk_personaggio' => $fkPersonaggio,
                'fk_classe' => $classId,
                'descrizione' => $description,
                'suffisso_costume' => $suffix,
            ]);

            $newId = (int) $pdo->lastInsertId();
            if (in_array($type, ['Equipaggiamento', 'Pet'], true)) {
                $this->savePersonalizationAbilities($newId, $abilities);
            }

            return ['success' => true, 'message' => $this->t('teacher.customizations.created')];
        }

        $params = [
            'id_personalizzazione' => $id,
            'nome_personalizzazione' => $name,
            'tipo' => $type,
            'costo' => $cost,
            'fk_personaggio' => $fkPersonaggio,
            'descrizione' => $description,
            'suffisso_costume' => $suffix,
        ];

        $sql = 'UPDATE ct_personalizzazioni
                SET nome_personalizzazione = :nome_personalizzazione,
                    tipo = :tipo,
                    costo = :costo,
                    fk_personaggio = :fk_personaggio,
                    descrizione = :descrizione,
                    suffisso_costume = :suffisso_costume';

        if ($imagePath !== null) {
            $sql .= ', img = :img';
            $params['img'] = $imagePath;
        }

        $sql .= ' WHERE id_personalizzazione = :id_personalizzazione';
        $pdo->prepare($sql)->execute($params);

        if (in_array($type, ['Equipaggiamento', 'Pet'], true)) {
            $this->savePersonalizationAbilities($id, $abilities);
        } else {
            $this->savePersonalizationAbilities($id, []);
        }

        return ['success' => true, 'message' => $this->t('teacher.customizations.updated')];
    }

    public function uploadCostume(array $files): array
    {
        $file = is_array($files['img_costume'] ?? null) ? $files['img_costume'] : [];
        $path = $this->resolveUploadPath($file, '/assets/images/Personalizzazioni/Costumes', null,true);
        if ($path === null) {
            return $this->error($this->t('teacher.customizations.costume.invalid_file'));
        }

        return ['success' => true, 'message' => sprintf($this->t('teacher.customizations.costume.uploaded'), $path)];
    }

    public function getDiscountDaysPageData(): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['discountDays'] = [];
            return $data;
        }

        $stmt = Database::getConnection()->query('SELECT * FROM ct_giornate_sconti ORDER BY data DESC');
        $data['discountDays'] = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $data;
    }

    public function saveDiscountDay(array $input): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        $date = trim((string) ($input['data_sconto'] ?? ''));
        $discount = (int) ($input['percentuale_sconto'] ?? 0);
        $reason = trim((string) ($input['motivazione_sconto'] ?? ''));
        $recurrent = isset($input['sconto_ricorrente']) ? 1 : 0;

        if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $this->error($this->t('teacher.customizations.discount.date.invalid'));
        }
        if ($discount < 1 || $discount > 100) {
            return $this->error($this->t('teacher.customizations.discount.value.invalid'));
        }
        if ($reason === '') {
            return $this->error($this->t('teacher.customizations.discount.reason.required'));
        }

        Database::getConnection()->prepare(
            'INSERT INTO ct_giornate_sconti (data, motivazione, sconto, recurrent) VALUES (:data, :motivazione, :sconto, :recurrent)'
        )->execute([
            'data' => $date,
            'motivazione' => htmlentities($reason),
            'sconto' => $discount,
            'recurrent' => $recurrent,
        ]);

        return ['success' => true, 'message' => $this->t('teacher.customizations.discount.saved')];
    }

    public function deleteDiscountDay(int $id): array
    {
        if ($id <= 0) {
            return $this->error($this->t('teacher.customizations.discount.invalid'));
        }

        Database::getConnection()->prepare('DELETE FROM ct_giornate_sconti WHERE id_giornata = :id')->execute(['id' => $id]);
        return ['success' => true, 'message' => $this->t('teacher.customizations.discount.deleted')];
    }

    public function getStudentUploadsPageData(): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['pendingUploads'] = [];
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $stmt = Database::getConnection()->prepare(
            'SELECT p.*, u.nome, u.cognome
             FROM ct_personalizzazioni p
             INNER JOIN ct_studenti s ON s.id_studente = p.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE p.fk_classe = :fk_classe AND p.tipo = "Personale" AND p.approvata = 0
             ORDER BY u.cognome ASC, u.nome ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);
        $data['pendingUploads'] = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $data;
    }

    public function updateStudentUploadStatus(int $id, bool $approve): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        if ($id <= 0) {
            return $this->error($this->t('teacher.customizations.invalid'));
        }

        $upload = $this->getStudentUploadById($id, (int) ($base['classroom']['id_classe'] ?? 0));
        if ($upload === null) {
            return $this->error($this->t('teacher.customizations.student_upload.not_found'));
        }

        $status = $approve ? 1 : 2;
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $pdo->prepare('UPDATE ct_personalizzazioni SET approvata = :approvata WHERE id_personalizzazione = :id')
                ->execute(['approvata' => $status, 'id' => $id]);

            if ($approve) {
                $pdo->prepare(
                    'INSERT INTO ct_studente_personalizzazioni (fk_personalizzazione, fk_studente, in_uso, primo)
                     SELECT :fk_personalizzazione, :fk_studente, 0, 0
                     WHERE NOT EXISTS (
                         SELECT 1
                         FROM ct_studente_personalizzazioni
                         WHERE fk_personalizzazione = :fk_personalizzazione_check
                           AND fk_studente = :fk_studente_check
                     )'
                )->execute([
                    'fk_personalizzazione' => $id,
                    'fk_studente' => (int) ($upload['fk_studente'] ?? 0),
                    'fk_personalizzazione_check' => $id,
                    'fk_studente_check' => (int) ($upload['fk_studente'] ?? 0),
                ]);
            }

            $this->insertStudentAlert(
                (int) ($upload['fk_classe'] ?? 0),
                (int) ($upload['fk_studente'] ?? 0),
                $approve
                    ? $this->t('teacher.customizations.student_upload.alert.approved')
                    : $this->t('teacher.customizations.student_upload.alert.rejected'),
                $approve ? 'PersonalizzazioneApprovata' : 'PersonalizzazioneRifiutata'
            );

            $pdo->commit();
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($this->t('teacher.customizations.student_upload.status_error'));
        }

        return [
            'success' => true,
            'message' => $approve
                ? $this->t('teacher.customizations.student_upload.approved')
                : $this->t('teacher.customizations.student_upload.rejected'),
        ];
    }

    private function getStudentUploadById(int $id, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_personalizzazione, fk_studente, fk_classe
             FROM ct_personalizzazioni
             WHERE id_personalizzazione = :id
               AND fk_classe = :fk_classe
               AND tipo = "Personale"
               AND fk_studente IS NOT NULL
               AND approvata = 0
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'fk_classe' => $classId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function insertStudentAlert(int $classId, int $studentId, string $text, string $type): void
    {
        Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, testo, fk_studente, data_alert, tipologia, link, letto, doc_stud)
             VALUES (:fk_classe, :testo, :fk_studente, :data_alert, :tipologia, :link, 0, 1)'
        )->execute([
            'fk_classe' => $classId,
            'testo' => $text,
            'fk_studente' => $studentId,
            'data_alert' => date('Y-m-d H:i:s'),
            'tipologia' => $type,
            'link' => 'personalizza_personaggio.php',
        ]);
    }

    public function getInUsePageData(array $query): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['students'] = [];
            $data['activeCustomizations'] = [];
            $data['ownedCustomizations'] = [];
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $students = $this->getStudentsForClass($classId);
        $selectedStudentId = (int) ($query['studente'] ?? 0);
        if ($selectedStudentId <= 0 && !empty($students)) {
            $selectedStudentId = (int) ($students[0]['id_studente'] ?? 0);
        }

        $data['students'] = $students;
        $data['selectedStudentId'] = $selectedStudentId;
        $data['activeCustomizations'] = $selectedStudentId > 0 ? $this->getStudentCustomizations($selectedStudentId, true) : [];
        $data['ownedCustomizations'] = $selectedStudentId > 0 ? $this->getStudentCustomizations($selectedStudentId, false) : [];

        return $data;
    }

    public function getSetsPageData(): array
    {
        $data = $this->basePageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['sets'] = [];
            $data['setTypes'] = self::SET_TYPES;
            $data['personalizationsByType'] = [];
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $data['sets'] = $this->getSetsForClass();
        $data['setTypes'] = self::SET_TYPES;
        $data['personalizationsByType'] = $this->getAssignablePersonalizationsGroupedByType($classId);

        return $data;
    }

    public function saveSet(array $input): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        $id = (int) ($input['id_set'] ?? 0);
        $name = trim((string) ($input['nome_set'] ?? ''));
        $color = trim((string) ($input['colore_set'] ?? '#2f80ed'));
        $type = trim((string) ($input['tipologia'] ?? ''));

        if ($name === '' || !in_array($type, self::SET_TYPES, true)) {
            return $this->error($this->t('teacher.customizations.set.name_type.required'));
        }

        $pdo = Database::getConnection();
        if ($id <= 0) {
            $pdo->prepare(
                'INSERT INTO ct_set_personalizzazioni (nome_set, colore_set, tipologia)
                 VALUES (:nome_set, :colore_set, :tipologia)'
            )->execute([
                'nome_set' => $name,
                'colore_set' => $color,
                'tipologia' => $type,
            ]);

            return ['success' => true, 'message' => $this->t('teacher.customizations.set.created')];
        }

        $belongs = $this->findSetById($id);
        if ($belongs === null) {
            return $this->error($this->t('teacher.customizations.set.not_found_or_not_editable'));
        }

        $pdo->prepare(
            'UPDATE ct_set_personalizzazioni
             SET nome_set = :nome_set, colore_set = :colore_set, tipologia = :tipologia
             WHERE id_set = :id_set'
        )->execute([
            'id_set' => $id,
            'nome_set' => $name,
            'colore_set' => $color,
            'tipologia' => $type,
        ]);

        return ['success' => true, 'message' => $this->t('teacher.customizations.set.updated')];
    }

    public function assignSetPersonalizations(array $input): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $setId = (int) ($input['id_set'] ?? 0);
        $personalizationIds = [];
        foreach ((array) ($input['personalizzazioni'] ?? []) as $item) {
            $value = (int) $item;
            if ($value > 0) {
                $personalizationIds[] = $value;
            }
        }
        $personalizationIds = array_values(array_unique($personalizationIds));

        $set = $this->findSetById($setId);
        if ($set === null) {
            return $this->error($this->t('teacher.customizations.set.invalid'));
        }

        $setType = (string) ($set['tipologia'] ?? '');
        if (!in_array($setType, self::SET_TYPES, true)) {
            return $this->error($this->t('teacher.customizations.set.type.unsupported'));
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $clearStmt = $pdo->prepare(
                'UPDATE ct_personalizzazioni
                 SET fk_set = NULL
                 WHERE fk_set = :fk_set AND tipo = :tipo'
            );
            $clearStmt->execute([
                'fk_set' => $setId,
                'tipo' => $setType,
            ]);

            if ($personalizationIds !== []) {
                $allowed = $this->getAssignablePersonalizationIdsForType($classId, $setType);
                $validIds = array_values(array_intersect($allowed, $personalizationIds));
                if ($validIds !== []) {
                    $placeholders = implode(',', array_fill(0, count($validIds), '?'));
                    $params = array_merge([$setId], $validIds);
                    $pdo->prepare(
                        'UPDATE ct_personalizzazioni SET fk_set = ?
                         WHERE id_personalizzazione IN (' . $placeholders . ')'
                    )->execute($params);
                }
            }

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('teacher.customizations.set.assigned')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error(sprintf($this->t('teacher.customizations.set.assign.error'), $exception->getMessage()));
        }
    }

    public function buildSetExportArchive(int $setId): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }
        $set = $this->findSetById($setId);
        if ($set === null) {
            return $this->error($this->t('teacher.customizations.set.not_found'));
        }

        $payload = $this->buildSetExportPayload($setId);
        $tmpDir = sys_get_temp_dir() . '/chronoquest_set_export_' . uniqid('', true);
        if (!mkdir($tmpDir, 0775, true) && !is_dir($tmpDir)) {
            return $this->error($this->t('teacher.customizations.export.temp_folder_failed'));
        }

        file_put_contents($tmpDir . '/set.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        foreach ($this->collectSetAssetPaths($payload) as $assetPath) {
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

        $safeName = preg_replace('/[^a-z0-9_-]+/i', '_', (string) ($set['nome_set'] ?? 'set'));
        $zipPath = sys_get_temp_dir() . '/chronoquest_set_' . $safeName . '_' . date('Ymd_His') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->deleteDirectory($tmpDir);
            return $this->error($this->t('teacher.customizations.export.zip_failed'));
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

    public function importSetFromArchive(array $files): array
    {
        $base = $this->basePageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('teacher.customizations.permission_denied'));
        }

        $archive = is_array($files['set_archive'] ?? null) ? $files['set_archive'] : [];
        if (($archive['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->t('teacher.customizations.import.archive.required'));
        }

        $tmpName = (string) ($archive['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->t('teacher.customizations.import.invalid_zip_file'));
        }

        $extractDir = sys_get_temp_dir() . '/chronoquest_set_import_' . uniqid('', true);
        if (!mkdir($extractDir, 0775, true) && !is_dir($extractDir)) {
            return $this->error($this->t('teacher.customizations.import.prepare_failed'));
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->t('teacher.customizations.import.open_zip_failed'));
        }
        $zip->extractTo($extractDir);
        $zip->close();

        $jsonPath = $extractDir . '/set.json';
        if (!is_file($jsonPath)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->t('teacher.customizations.import.set_json_missing'));
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        if (!is_array($payload)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->t('teacher.customizations.import.invalid_json'));
        }

        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $this->restoreAssetsFromArchive($extractDir, $this->isEquipmentSetPayload($payload));
            $this->importSetPayload($pdo, $payload, $classId);
            $pdo->commit();
            $this->deleteDirectory($extractDir);
            return ['success' => true, 'message' => $this->t('teacher.customizations.import.success')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deleteDirectory($extractDir);
            return $this->error(sprintf($this->t('teacher.customizations.import.error_with_message'), $exception->getMessage()));
        }
    }

    private function getStudentCustomizations(int $studentId, bool $onlyInUse): array
    {
        $sql = 'SELECT sp.in_uso, p.*
                FROM ct_studente_personalizzazioni sp
                INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
                WHERE sp.fk_studente = :fk_studente';
        if ($onlyInUse) {
            $sql .= ' AND sp.in_uso = 1';
        }
        $sql .= ' ORDER BY p.tipo ASC, p.nome_personalizzazione ASC';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['fk_studente' => $studentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getPersonalizationsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.*, ch.nome_personaggio
             FROM ct_personalizzazioni p
             LEFT JOIN ct_personaggi ch ON ch.id_personaggio = p.fk_personaggio
             WHERE (p.fk_classe = :fk_classe OR p.tipo IN ("Sfondo", "Pet", "Equipaggiamento", "BigBackground"))
             ORDER BY p.tipo ASC, p.nome_personalizzazione ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getCharactersForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare('SELECT id_personaggio, nome_personaggio FROM ct_personaggi WHERE fk_classe = :fk_classe ORDER BY nome_personaggio ASC');
        $stmt->execute(['fk_classe' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getStudentsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, u.nome, u.cognome
             FROM ct_studenti_classi sc
             INNER JOIN ct_studenti s ON s.id_studente = sc.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE sc.fk_classe = :fk_classe
             ORDER BY u.cognome ASC, u.nome ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findEditablePersonalization(int $classId, int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_personalizzazioni
             WHERE id_personalizzazione = :id
               AND fk_classe = :fk_classe
               AND (fk_studente IS NULL OR fk_studente = 0)'
        );
        $stmt->execute(['id' => $id, 'fk_classe' => $classId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    private function getAbilitiesByType(bool $pet): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.id_abilita,
                    a.tipologia,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_abilita\' and t.nome_campo=\'tipologia\' and t.lingua=\'en\' and t.fk_collegamento=a.id_abilita) as tipologia_en
             FROM ct_abilita a
             WHERE equipet = :equipet
             ORDER BY tipologia ASC'
        );
        $stmt->execute(['equipet' => $pet ? 1 : 0]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getPersonalizationAbilities(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ae.fk_personalizzazione, ae.fk_abilita, ae.aumento
             FROM ct_abilita_equipaggiamento ae
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = ae.fk_personalizzazione
             WHERE p.fk_classe = :fk_classe OR p.tipo IN ("Pet", "Equipaggiamento")'
        );
        $stmt->execute(['fk_classe' => $classId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $mapped = [];
        foreach ($rows as $row) {
            $personalizationId = (int) ($row['fk_personalizzazione'] ?? 0);
            if ($personalizationId <= 0) {
                continue;
            }
            if (!isset($mapped[$personalizationId])) {
                $mapped[$personalizationId] = [];
            }
            $mapped[$personalizationId][] = [
                'id' => (int) ($row['fk_abilita'] ?? 0),
                'aumento' => (int) ($row['aumento'] ?? 0),
            ];
        }

        return $mapped;
    }

    private function sanitizeAbilities(mixed $rawAbilities): array
    {
        if (!is_string($rawAbilities) || trim($rawAbilities) === '') {
            return [];
        }

        $decoded = json_decode($rawAbilities, true);
        if (!is_array($decoded)) {
            return [];
        }

        $abilities = [];
        foreach ($decoded as $ability) {
            if (!is_array($ability)) {
                continue;
            }

            $abilityId = (int) ($ability['id'] ?? 0);
            if ($abilityId <= 0) {
                continue;
            }

            $abilities[] = [
                'id' => $abilityId,
                'aumento' => (int) ($ability['aumento'] ?? 0),
            ];
        }

        return $abilities;
    }

    private function savePersonalizationAbilities(int $personalizationId, array $abilities): void
    {
        if ($personalizationId <= 0) {
            return;
        }

        $pdo = Database::getConnection();
        $pdo->prepare('DELETE FROM ct_abilita_equipaggiamento WHERE fk_personalizzazione = :fk_personalizzazione')
            ->execute(['fk_personalizzazione' => $personalizationId]);

        if ($abilities === []) {
            return;
        }

        $insert = $pdo->prepare(
            'INSERT INTO ct_abilita_equipaggiamento (fk_abilita, fk_personalizzazione, aumento)
             VALUES (:fk_abilita, :fk_personalizzazione, :aumento)'
        );
        foreach ($abilities as $ability) {
            $insert->execute([
                'fk_abilita' => (int) ($ability['id'] ?? 0),
                'fk_personalizzazione' => $personalizationId,
                'aumento' => (int) ($ability['aumento'] ?? 0),
            ]);
        }
    }

    private function getSetsForClass(): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.*,
                    COUNT(p.id_personalizzazione) AS totale_personalizzazioni
             FROM ct_set_personalizzazioni s
             LEFT JOIN ct_personalizzazioni p ON p.fk_set = s.id_set
             GROUP BY s.id_set, s.nome_set, s.colore_set, s.tipologia
             ORDER BY s.tipologia ASC, s.nome_set ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findSetById(int $setId): ?array
    {
        if ($setId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_set_personalizzazioni
             WHERE id_set = :id_set'
        );
        $stmt->execute(['id_set' => $setId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    private function getAssignablePersonalizationsGroupedByType(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_personalizzazione, nome_personalizzazione, tipo, fk_set
             FROM ct_personalizzazioni
             WHERE approvata = 1
               AND tipo IN ("Equipaggiamento", "Pet", "BigBackground", "Sfondo")
               AND (
                    fk_classe = :fk_classe
                    OR tipo IN ("Equipaggiamento", "Pet", "BigBackground", "Sfondo")
               )
             ORDER BY tipo ASC, nome_personalizzazione ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $grouped = [];
        foreach (self::SET_TYPES as $type) {
            $grouped[$type] = [];
        }

        foreach ($rows as $row) {
            $type = (string) ($row['tipo'] ?? '');
            if (!isset($grouped[$type])) {
                continue;
            }
            $grouped[$type][] = $row;
        }

        return $grouped;
    }

    private function getAssignablePersonalizationIdsForType(int $classId, string $type): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_personalizzazione
             FROM ct_personalizzazioni
             WHERE approvata = 1
               AND tipo = :tipo
               AND (fk_classe = :fk_classe OR tipo IN ("Equipaggiamento", "Pet", "BigBackground", "Sfondo"))'
        );
        $stmt->execute([
            'tipo' => $type,
            'fk_classe' => $classId,
        ]);

        return array_map(static fn (array $row): int => (int) ($row['id_personalizzazione'] ?? 0), $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function buildSetExportPayload(int $setId): array
    {
        $setStmt = Database::getConnection()->prepare('SELECT * FROM ct_set_personalizzazioni WHERE id_set = :id_set');
        $setStmt->execute(['id_set' => $setId]);
        $set = $setStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $personalizationStmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_personalizzazioni
             WHERE fk_set = :fk_set
             ORDER BY nome_personalizzazione ASC'
        );
        $personalizationStmt->execute(['fk_set' => $setId]);
        $customizations = $personalizationStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $ids = array_values(array_filter(array_map(static fn (array $row): int => (int) ($row['id_personalizzazione'] ?? 0), $customizations)));
        $abilitiesByCustomization = [];
        if ($ids !== []) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $abilityStmt = Database::getConnection()->prepare(
                'SELECT fk_personalizzazione, fk_abilita, aumento
                 FROM ct_abilita_equipaggiamento
                 WHERE fk_personalizzazione IN (' . $placeholders . ')'
            );
            $abilityStmt->execute($ids);
            foreach ($abilityStmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $abilityRow) {
                $personalizationId = (int) ($abilityRow['fk_personalizzazione'] ?? 0);
                if ($personalizationId <= 0) {
                    continue;
                }
                if (!isset($abilitiesByCustomization[$personalizationId])) {
                    $abilitiesByCustomization[$personalizationId] = [];
                }
                $abilitiesByCustomization[$personalizationId][] = [
                    'fk_abilita' => (int) ($abilityRow['fk_abilita'] ?? 0),
                    'aumento' => (int) ($abilityRow['aumento'] ?? 0),
                ];
            }
        }

        return [
            'schema' => 'chronoquest.set.v1',
            'exported_at' => date(DATE_ATOM),
            'set' => $set,
            'personalizzazioni' => array_map(function (array $item) use ($abilitiesByCustomization): array {
                $id = (int) ($item['id_personalizzazione'] ?? 0);
                $item['abilita'] = $abilitiesByCustomization[$id] ?? [];
                return $item;
            }, $customizations),
        ];
    }

    private function collectSetAssetPaths(array $payload): array
    {
        $paths = [];
        foreach ((array) ($payload['personalizzazioni'] ?? []) as $customization) {
            $img = trim((string) ($customization['img'] ?? ''));
            if ($img !== '' && preg_match('/^https?:\/\//i', $img) !== 1 && str_contains($img, 'assets/')) {
                $paths[] = str_starts_with($img, '/') ? $img : '/' . ltrim($img, '/');
            }
        }

        if ($this->isEquipmentSetPayload($payload)) {
            foreach ($this->collectCostumeAssetPathsForEquipmentSet($payload) as $costumePath) {
                $paths[] = $costumePath;
            }
        }

        return array_values(array_unique($paths));
    }

    private function isEquipmentSetPayload(array $payload): bool
    {
        $set = (array) ($payload['set'] ?? []);
        return (string) ($set['tipologia'] ?? '') === 'Equipaggiamento';
    }

    private function collectCostumeAssetPathsForEquipmentSet(array $payload): array
    {
        $suffixes = [];
        foreach ((array) ($payload['personalizzazioni'] ?? []) as $customization) {
            $suffix = trim((string) ($customization['suffisso_costume'] ?? ''));
            if ($suffix !== '') {
                $suffixes[] = $suffix;
            }
        }

        $suffixes = array_values(array_unique($suffixes));
        if ($suffixes === []) {
            return [];
        }

        $costumeDir = dirname(__DIR__, 2) . '/public/assets/images/Personalizzazioni/Costumes';
        if (!is_dir($costumeDir)) {
            return [];
        }

        $paths = [];
        foreach (array_diff(scandir($costumeDir) ?: [], ['.', '..']) as $file) {
            $absolutePath = $costumeDir . '/' . $file;
            if (!is_file($absolutePath)) {
                continue;
            }

            foreach ($suffixes as $suffix) {
                if (str_ends_with($file, $suffix)) {
                    $paths[] = '/assets/images/Personalizzazioni/Costumes/' . $file;
                    break;
                }
            }
        }

        return $paths;
    }

    private function importSetPayload(PDO $pdo, array $payload, int $classId): void
    {
        $set = (array) ($payload['set'] ?? []);
        $type = (string) ($set['tipologia'] ?? '');
        if (!in_array($type, self::SET_TYPES, true)) {
            throw new \RuntimeException($this->t('teacher.customizations.import.set_type.invalid'));
        }

        $pdo->prepare(
            'INSERT INTO ct_set_personalizzazioni (nome_set, colore_set, tipologia)
             VALUES (:nome_set, :colore_set, :tipologia)'
        )->execute([
            'nome_set' => (string) (($set['nome_set'] ?? '') ?: $this->t('teacher.customizations.import.default_set_name')),
            'colore_set' => (string) (($set['colore_set'] ?? '') ?: '#2f80ed'),
            'tipologia' => $type,
        ]);
        $newSetId = (int) $pdo->lastInsertId();

        foreach ((array) ($payload['personalizzazioni'] ?? []) as $customization) {
            $fkPersonaggio = (int) ($customization['fk_personaggio'] ?? 0);
            $customType = (string) ($customization['tipo'] ?? '');
            $assignCharacter = in_array($customType, ['Capelli', 'Equipaggiamento'], true) ? $fkPersonaggio : 0;

            $pdo->prepare(
                'INSERT INTO ct_personalizzazioni
                    (uuid, nome_personalizzazione, img, tipo, costo, fk_personaggio, fk_classe, fk_studente, approvata, descrizione, suffisso_costume, fk_set)
                 VALUES
                    (:uuid, :nome_personalizzazione, :img, :tipo, :costo, :fk_personaggio, :fk_classe, NULL, 1, :descrizione, :suffisso_costume, :fk_set)'
            )->execute([
                'uuid' => (string) (($customization['uuid'] ?? '') ?: $this->generateUuidV4()),
                'nome_personalizzazione' => (string) ($customization['nome_personalizzazione'] ?? ''),
                'img' => $this->normalizeAssetPathForDb((string) ($customization['img'] ?? '')),
                'tipo' => $customType,
                'costo' => (int) ($customization['costo'] ?? 0),
                'fk_personaggio' => $assignCharacter,
                'fk_classe' => $classId,
                'descrizione' => (string) ($customization['descrizione'] ?? ''),
                'suffisso_costume' => (string) ($customization['suffisso_costume'] ?? ''),
                'fk_set' => $newSetId,
            ]);

            $newPersId = (int) $pdo->lastInsertId();
            foreach ((array) ($customization['abilita'] ?? []) as $ability) {
                $abilityId = (int) ($ability['fk_abilita'] ?? 0);
                if ($abilityId <= 0) {
                    continue;
                }
                $pdo->prepare(
                    'INSERT INTO ct_abilita_equipaggiamento (fk_abilita, fk_personalizzazione, aumento)
                     VALUES (:fk_abilita, :fk_personalizzazione, :aumento)'
                )->execute([
                    'fk_abilita' => $abilityId,
                    'fk_personalizzazione' => $newPersId,
                    'aumento' => (int) ($ability['aumento'] ?? 0),
                ]);
            }
        }
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

    private function restoreAssetsFromArchive(string $extractDir, bool $includeCostumes): void
    {
        $source = $extractDir . '/assets/images';
        if (!is_dir($source)) {
            return;
        }

        $destination = dirname(__DIR__, 2) . '/public/assets/images';
        $this->copyDirectory($source, $destination, $includeCostumes);
    }

    private function copyDirectory(string $source, string $destination, bool $includeCostumes): void
    {
        if (!is_dir($source)) {
            return;
        }

        if (!is_dir($destination) && !mkdir($destination, 0775, true) && !is_dir($destination)) {
            throw new \RuntimeException($this->t('teacher.customizations.import.asset_folder_create_failed'));
        }

        foreach (array_diff(scandir($source) ?: [], ['.', '..']) as $entry) {
            $sourcePath = $source . '/' . $entry;
            $destinationPath = $destination . '/' . $entry;
            if (!$includeCostumes && $this->isCostumeArchivePath($sourcePath)) {
                continue;
            }

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destinationPath, $includeCostumes);
                continue;
            }

            $destinationDir = dirname($destinationPath);
            if (!is_dir($destinationDir) && !mkdir($destinationDir, 0775, true) && !is_dir($destinationDir)) {
                throw new \RuntimeException($this->t('teacher.customizations.import.asset_destination_failed'));
            }

            copy($sourcePath, $destinationPath);
        }
    }

    private function isCostumeArchivePath(string $path): bool
    {
        return str_contains(str_replace('\\', '/', $path), '/Personalizzazioni/Costumes');
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

    private function basePageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe'
        );
        $stmt->execute(['id_classe' => $classId]);

        $classroom = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!is_array($classroom)) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $classroom;
        return $data;
    }

    private function resolveUploadPath(array $file, string $publicDir, ?string $fallback = null,bool $isCostume=false): ?string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return $fallback;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->t('teacher.customizations.upload.error'));
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->t('teacher.customizations.upload.invalid_file'));
        }

        $originalName = (string) ($file['name'] ?? 'file');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException($this->t('teacher.customizations.upload.unsupported_format'));
        }

        $safeName = preg_replace('/[^a-z0-9_-]+/i', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $safeName = trim((string) $safeName, '_');
        if ($safeName === '') {
            $safeName = 'asset';
        }
        if($isCostume) {
            $fileName = $safeName. '.' . $extension;
        }
        else {
            $fileName = $safeName . '_' . date('Ymd_His') . '_' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $extension;
        }
        $relativeDir = '/' . trim($publicDir, '/');
        $absoluteDir = dirname(__DIR__, 2) . '/public' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->t('teacher.customizations.upload.create_folder_failed'));
        }

        $absolutePath = $absoluteDir . '/' . $fileName;
        if (!move_uploaded_file($tmpName, $absolutePath)) {
            throw new \RuntimeException($this->t('teacher.customizations.upload.save_failed'));
        }

        return $relativeDir . '/' . $fileName;
    }

    private function error(string $message): array
    {
        return ['success' => false, 'message' => $message];
    }

    private function t(string $key): string
    {
        return $this->translator->translate($key);
    }

    private function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        $hex = bin2hex($data);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}
