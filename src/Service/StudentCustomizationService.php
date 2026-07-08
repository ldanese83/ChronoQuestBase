<?php

namespace App\Service;

use PDO;
use Throwable;

class StudentCustomizationService
{
    private const CUSTOMIZATION_TYPES = ['Sfondo', 'BigBackground', 'Capelli', 'Personale', 'Pet'];
    private const STUDENT_UPLOAD_COST = 800;
    private const STUDENT_UPLOAD_MAX_BYTES = 5242880;

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getCustomizationPageData(): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $characterId = (int) ($data['student']['fk_personaggio'] ?? 0);

        $data['character'] = $this->getCharacter($characterId);
        $data['activeByType'] = $this->getActiveCustomizationsByType($studentId);
        $data['ownedByType'] = $this->getOwnedCustomizationsByType($studentId);
        $data['hasAnyCustomization'] = array_sum(array_map('count', $data['ownedByType'])) > 0;

        return $data;
    }

    public function saveCustomizationSelection(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $selectedIds = [
            'Sfondo' => (int) ($input['sfondo_scelto'] ?? 0),
            'BigBackground' => (int) ($input['bigsfondo_scelto'] ?? 0),
            'Capelli' => (int) ($input['capelli_scelti'] ?? 0),
            'Personale' => (int) ($input['personale_scelto'] ?? 0),
            'Pet' => (int) ($input['id_pet'] ?? 0),
        ];

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $petBefore = (int) ($this->getCurrentActiveCustomizationIdByType($studentId, 'Pet') ?? 0);
            if($petBefore!=0)
                $this->applyPetBonusDelta($studentId, $petBefore, false);

            foreach ($selectedIds as $type => $selectedId) {
                $this->clearTypeInUse($studentId, $type);
                if ($selectedId > 0 && $this->isOwnedOfType($studentId, $selectedId, $type)) {
                    $this->setInUse($studentId, $selectedId, true);
                }
            }
            
            $petAfter = (int) ($selectedIds['Pet'] ?? 0);

            if ($petAfter > 0 && $this->isOwnedOfType($studentId, $petAfter, 'Pet')) {
                $this->applyPetBonusDelta($studentId, $petAfter, true);
            }

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.customization.service.save.success')];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errore = $pdo->errorInfo();
            return $this->error($this->t('student.customization.service.save.error') . $errore[2]);
        }
    }

    public function sellCustomization(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $customizationId = (int) ($input['id_personalizzazione'] ?? 0);
        if ($customizationId <= 0) {
            return $this->error($this->t('student.customization.service.invalid'));
        }

        $owned = $this->getOwnedCustomization($studentId, $customizationId);
        if ($owned === null) {
            return $this->error($this->t('student.customization.service.not_owned'));
        }

        $refund = (int) round(((int) ($owned['costo'] ?? 0)) * 0.6);
        if ((string) ($owned['tipo'] ?? '') === 'Personale' && (int) ($owned['costo'] ?? 0) === 1000) {
            $refund = 600;
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ((int) ($owned['in_uso'] ?? 0) === 1) {
                if ((string) ($owned['tipo'] ?? '') === 'Pet') {
                    $this->applyPetBonusDelta($studentId, $customizationId, false);
                }
                if ((string) ($owned['tipo'] ?? '') === 'Equipaggiamento') {
                    $this->resetStudentShield($studentId);
                }
            }

            $pdo->prepare('DELETE FROM ct_studente_personalizzazioni WHERE fk_studente = :s AND fk_personalizzazione = :p')
                ->execute(['s' => $studentId, 'p' => $customizationId]);

            $pdo->prepare('UPDATE ct_studenti SET monete = monete + :refund WHERE id_studente = :id')
                ->execute(['refund' => $refund, 'id' => $studentId]);

            $pdo->commit();

            return ['success' => true, 'message' => sprintf($this->t('student.customization.service.sell.success'), $refund)];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($this->t('student.customization.service.sell.error'));
        }
    }

    public function getShopPageData(): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $characterId = (int) ($data['student']['fk_personaggio'] ?? 0);

        $discount = $this->getActiveDiscount();
        $data['sconto_attivo'] = $discount['value'];
        $data['motivazioni_sconti'] = $discount['motivations'];

        $data['shopSections'] = [
            'Sfondo' => $this->getPurchasable($studentId, 'Sfondo', 0, $classId, $discount['value']),
            'BigBackground' => $this->getPurchasable($studentId, 'BigBackground', 0, $classId, $discount['value']),
            'Capelli' => $this->getPurchasable($studentId, 'Capelli', $characterId, $classId, $discount['value']),
            'Pet' => $this->getPurchasable($studentId, 'Pet', 0, $classId, $discount['value']),
        ];

        return $data;
    }

    public function buyCustomization(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $coins = (int) ($data['student']['monete'] ?? 0);
        $characterId = (int) ($data['student']['fk_personaggio'] ?? 0);
        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $customizationId = (int) ($input['id_personalizzazione'] ?? 0);

        if ($customizationId <= 0) {
            return $this->error($this->t('student.customization.service.item_invalid'));
        }

        $item = $this->findPurchasableById($studentId, $customizationId, $characterId, $classId);
        if ($item === null) {
            return $this->error($this->t('student.customization.service.item_unavailable'));
        }

        $discount = $this->getActiveDiscount()['value'];
        $cost = $this->discountedPrice((int) ($item['costo'] ?? 0), $discount);
        if ($coins < $cost) {
            return $this->error($this->t('student.customization.service.not_enough_coins'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $pdo->prepare('INSERT INTO ct_studente_personalizzazioni (fk_studente, fk_personalizzazione, in_uso, primo) VALUES (:s, :p, 0, 0)')
                ->execute(['s' => $studentId, 'p' => $customizationId]);

            $pdo->prepare('UPDATE ct_studenti SET monete = monete - :cost WHERE id_studente = :id')
                ->execute(['cost' => $cost, 'id' => $studentId]);

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.customization.service.buy.success')];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($this->t('student.customization.service.buy.error'));
        }
    }

    public function uploadStudentCustomization(array $input, array $files): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $coins = (int) ($data['student']['monete'] ?? 0);
        $name = trim((string) ($input['nome_personalizzazione'] ?? ''));
        $file = is_array($files['immagine_personalizzazione'] ?? null) ? $files['immagine_personalizzazione'] : [];

        if ($name === '') {
            return $this->error($this->t('student.customization.service.upload.name_required'));
        }

        if ($coins < self::STUDENT_UPLOAD_COST) {
            return $this->error($this->t('student.customization.service.upload.not_enough_coins'));
        }

        $imagePath = null;
        $pdo = Database::getConnection();

        try {
            $imagePath = $this->saveStudentCustomizationImage($file);

            $pdo->beginTransaction();

            $pdo->prepare(
                'INSERT INTO ct_personalizzazioni
                    (uuid, nome_personalizzazione, img, tipo, costo, fk_personaggio, fk_classe, fk_studente, approvata)
                 VALUES
                    (:uuid, :nome_personalizzazione, :img, "Personale", :costo, 0, :fk_classe, :fk_studente, 0)'
            )->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_personalizzazione' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'img' => $imagePath,
                'costo' => self::STUDENT_UPLOAD_COST,
                'fk_classe' => $classId,
                'fk_studente' => $studentId,
            ]);

            $pdo->prepare('UPDATE ct_studenti SET monete = monete - :cost WHERE id_studente = :id')
                ->execute(['cost' => self::STUDENT_UPLOAD_COST, 'id' => $studentId]);

            $studentName = $this->getStudentFullName($studentId);
            $this->insertTeacherAlert(
                $classId,
                sprintf($this->t('student.customization.service.upload.teacher_alert'), $studentName),
                'Personalizzazioni',
                'all_personal_studenti.php'
            );

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->t('student.customization.service.upload.success'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($imagePath !== null) {
                $this->deletePublicFile($imagePath);
            }

            return $this->error(sprintf($this->t('student.customization.service.upload.error'), $exception->getMessage()));
        }
    }

    public function getEquipmentPageData(): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $data['equipment'] = $this->getEquipmentCards($studentId);

        return $data;
    }

    public function buyEquipment(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $equipmentId = (int) ($input['id_equip'] ?? 0);
        if ($equipmentId <= 0) {
            return $this->error($this->t('student.customization.service.equipment_invalid'));
        }

        return $this->buyCustomization(['id_personalizzazione' => $equipmentId]);
    }

    public function equipEquipment(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $equipmentId = (int) ($input['id_equip'] ?? 0);
        if ($equipmentId <= 0 || !$this->isOwnedOfType($studentId, $equipmentId, 'Equipaggiamento')) {
            return $this->error($this->t('student.customization.service.equipment_not_owned'));
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $row = $pdo->prepare('SELECT primo FROM ct_studente_personalizzazioni WHERE fk_studente = :s AND fk_personalizzazione = :p LIMIT 1');
            $row->execute(['s' => $studentId, 'p' => $equipmentId]);
            $owned = $row->fetch(PDO::FETCH_ASSOC) ?: ['primo' => 0];

            if ((int) ($owned['primo'] ?? 0) === 0) {
                $pdo->prepare('UPDATE ct_studente_personalizzazioni SET primo = 1 WHERE fk_studente = :s AND fk_personalizzazione = :p')
                    ->execute(['s' => $studentId, 'p' => $equipmentId]);
            }

            $this->clearTypeInUse($studentId, 'Equipaggiamento');
            $this->setInUse($studentId, $equipmentId, true);
            $this->applyEquipmentShield($studentId, $equipmentId, (int) ($owned['primo'] ?? 0) === 0);

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.customization.service.equip.success')];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($this->t('student.customization.service.equip.error'));
        }
    }

    public function sellEquipment(array $input): array
    {
        $data = $this->baseData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.customization.service.permission_denied'));
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $equipmentId = (int) ($input['id_equip'] ?? 0);
        if ($equipmentId <= 0) {
            return $this->error($this->t('student.customization.service.equipment_invalid'));
        }

        $owned = $this->getOwnedCustomization($studentId, $equipmentId);
        if ($owned === null || (string) ($owned['tipo'] ?? '') !== 'Equipaggiamento') {
            return $this->error($this->t('student.customization.service.equipment_not_owned'));
        }

        $refund = (int) round(((int) ($owned['costo'] ?? 0)) / 2);
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ((int) ($owned['in_uso'] ?? 0) === 1) {
                $this->resetStudentShield($studentId);
            }

            $pdo->prepare('DELETE FROM ct_studente_personalizzazioni WHERE fk_studente = :s AND fk_personalizzazione = :p')
                ->execute(['s' => $studentId, 'p' => $equipmentId]);

            $pdo->prepare('UPDATE ct_studenti SET monete = monete + :refund WHERE id_studente = :id')
                ->execute(['refund' => $refund, 'id' => $studentId]);

            $pdo->commit();

            return ['success' => true, 'message' => sprintf($this->t('student.customization.service.equipment_sell.success'), $refund)];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($this->t('student.customization.service.equipment_sell.error'));
        }
    }

    private function baseData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $data = ['permissionStatus' => $permissionStatus, 'classroom' => null, 'student' => null];

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
            'SELECT c.id_classe, c.nome_classe, c.icona, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id AND c.eliminata = 0 LIMIT 1'
        );
        $stmt->execute(['id' => $classId]);
        $classroom = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($classroom === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, s.fk_personaggio, s.monete, s.vite, s.vite_massime, s.mana, s.mana_massimo, s.scudi, s.scudi_massimi
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.fk_utente = :u AND sc.fk_classe = :c LIMIT 1'
        );
        $stmt->execute(['u' => $userId, 'c' => $classId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($student === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $data['classroom'] = $classroom;
        $data['student'] = $student;

        return $data;
    }

    private function getCharacter(int $characterId): ?array
    {
        if ($characterId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare('SELECT id_personaggio, nome_personaggio, img_senza_sfondo, bordercolor, color FROM ct_personaggi WHERE id_personaggio = :id LIMIT 1');
        $stmt->execute(['id' => $characterId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getOwnedCustomizationsByType(int $studentId): array
    {
        $result = array_fill_keys(self::CUSTOMIZATION_TYPES, []);

        $stmt = Database::getConnection()->prepare(
            'SELECT sp.in_uso, p.*
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :id
             ORDER BY p.nome_personalizzazione ASC'
        );
        $stmt->execute(['id' => $studentId]);

        foreach (($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) as $row) {
            $type = (string) ($row['tipo'] ?? '');
            if (!array_key_exists($type, $result)) {
                continue;
            }
            $row['img_src'] = $this->normalizeImagePath((string) ($row['img'] ?? ''));
            $row['refund'] = (int) ((int) ($row['costo'] ?? 0) === 1000 ? 600 : round(((int) ($row['costo'] ?? 0)) * 0.6));
            $row['abilities'] = $type === 'Pet' ? $this->getEquipmentAbilities((int) ($row['id_personalizzazione'] ?? 0), true) : [];
            $result[$type][] = $row;
        }

        return $result;
    }

    private function getActiveCustomizationsByType(int $studentId): array
    {
        $result = array_fill_keys(self::CUSTOMIZATION_TYPES, null);

        $stmt = Database::getConnection()->prepare(
            'SELECT p.*
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :s AND sp.in_uso = 1'
        );
        $stmt->execute(['s' => $studentId]);

        foreach (($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) as $row) {
            $type = (string) ($row['tipo'] ?? '');
            if (array_key_exists($type, $result)) {
                $row['img_src'] = $this->normalizeImagePath((string) ($row['img'] ?? ''));
                $result[$type] = $row;
            }
        }

        return $result;
    }

    private function getCurrentActiveCustomizationIdByType(int $studentId, string $type): ?int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT sp.fk_personalizzazione
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :s AND sp.in_uso = 1 AND p.tipo = :t
             LIMIT 1'
        );
        $stmt->execute(['s' => $studentId, 't' => $type]);
        $id = $stmt->fetchColumn();
        return $id === false ? null : (int) $id;
    }

    private function clearTypeInUse(int $studentId, string $type): void
    {
        Database::getConnection()->prepare(
            'UPDATE ct_studente_personalizzazioni
             SET in_uso = 0
             WHERE fk_studente = :s
               AND fk_personalizzazione IN (
                    SELECT id_personalizzazione FROM ct_personalizzazioni WHERE tipo = :t
               )'
        )->execute(['s' => $studentId, 't' => $type]);
    }

    private function setInUse(int $studentId, int $customizationId, bool $inUse): void
    {
        Database::getConnection()->prepare(
            'UPDATE ct_studente_personalizzazioni
             SET in_uso = :i
             WHERE fk_studente = :s AND fk_personalizzazione = :p'
        )->execute([
            'i' => $inUse ? 1 : 0,
            's' => $studentId,
            'p' => $customizationId,
        ]);
    }

    private function isOwnedOfType(int $studentId, int $customizationId, string $type): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :s AND sp.fk_personalizzazione = :p AND p.tipo = :t'
        );
        $stmt->execute(['s' => $studentId, 'p' => $customizationId, 't' => $type]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function getOwnedCustomization(int $studentId, int $customizationId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT sp.in_uso, p.costo, p.tipo
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :s AND sp.fk_personalizzazione = :p
             LIMIT 1'
        );
        $stmt->execute(['s' => $studentId, 'p' => $customizationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getActiveDiscount(): array
    {
        $today = date('Y-m-d');
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM ct_giornate_sconti
             WHERE data = :today OR (recurrent = 1 AND DATE_FORMAT(data, "%m-%d") = DATE_FORMAT(:today2, "%m-%d"))
             ORDER BY sconto DESC, id_giornata DESC'
        );
        $stmt->execute(['today' => $today, 'today2' => $today]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $value = $rows !== [] ? (int) ($rows[0]['sconto'] ?? 0) : 0;
        $motivations = [];
        foreach ($rows as $row) {
            $motivations[] = html_entity_decode((string) ($row['motivazione'] ?? ''));
        }

        return ['value' => $value, 'motivations' => $motivations];
    }

    private function discountedPrice(int $baseCost, int $discount): int
    {
        if ($discount <= 0) {
            return $baseCost;
        }

        $factor = max(0, 100 - $discount);
        return (int) round(($baseCost * $factor) / 100);
    }

    private function getPurchasable(int $studentId, string $type, int $characterId, int $classId, int $discount): array
    {
        $sql = 'SELECT p.*
                FROM ct_personalizzazioni p
                WHERE p.id_personalizzazione NOT IN (
                    SELECT fk_personalizzazione FROM ct_studente_personalizzazioni WHERE fk_studente = :s
                )
                  AND p.tipo = :t
                  AND ((p.fk_classe = :c OR p.fk_classe IS NULL OR p.fk_classe = 0) OR p.tipo IN ("Sfondo", "Pet", "Equipaggiamento", "BigBackground"))';

        $params = ['s' => $studentId, 't' => $type, 'c' => $classId];

        if ($type === 'Capelli') {
            $sql .= ' AND p.fk_personaggio = :ch';
            $params['ch'] = $characterId;
        }

        $sql .= ' ORDER BY p.costo ASC, p.nome_personalizzazione ASC';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($rows as &$row) {
            $row['img_src'] = $this->normalizeImagePath((string) ($row['img'] ?? ''));
            $row['costo_finale'] = $this->discountedPrice((int) ($row['costo'] ?? 0), $discount);
            $row['abilities'] = in_array($type, ['Capelli', 'Pet'], true)
                ? $this->getEquipmentAbilities((int) ($row['id_personalizzazione'] ?? 0), true)
                : [];
        }

        return $rows;
    }

    private function findPurchasableById(int $studentId, int $customizationId, int $characterId, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.*
             FROM ct_personalizzazioni p
             WHERE p.id_personalizzazione = :id
               AND p.id_personalizzazione NOT IN (
                    SELECT fk_personalizzazione FROM ct_studente_personalizzazioni WHERE fk_studente = :s
               )
               AND ((p.fk_classe = :c OR p.fk_classe IS NULL OR p.fk_classe = 0) OR p.tipo IN ("Sfondo", "Pet", "Equipaggiamento", "BigBackground"))
             LIMIT 1'
        );

        $stmt->execute(['id' => $customizationId, 's' => $studentId, 'c' => $classId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($row === null) {
            return null;
        }

        if ((string) ($row['tipo'] ?? '') === 'Capelli' && (int) ($row['fk_personaggio'] ?? 0) !== $characterId) {
            return null;
        }

        return $row;
    }

    private function getEquipmentCards(int $studentId): array
    {
        $stmt = Database::getConnection()->query('SELECT * FROM ct_personalizzazioni WHERE tipo = "Equipaggiamento" ORDER BY costo ASC, nome_personalizzazione ASC');
        $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($equipment as &$row) {
            $row['img_src'] = $this->normalizeImagePath((string) ($row['img'] ?? ''));
            $row['abilities'] = $this->getEquipmentAbilities((int) ($row['id_personalizzazione'] ?? 0), false);

            $stateStmt = Database::getConnection()->prepare(
                'SELECT in_uso, primo FROM ct_studente_personalizzazioni WHERE fk_studente = :s AND fk_personalizzazione = :p LIMIT 1'
            );
            $stateStmt->execute(['s' => $studentId, 'p' => (int) ($row['id_personalizzazione'] ?? 0)]);
            $state = $stateStmt->fetch(PDO::FETCH_ASSOC) ?: null;

            $row['state'] = 'locked';
            if ($state !== null) {
                $row['state'] = ((int) ($state['in_uso'] ?? 0) === 1) ? 'equipped' : 'owned';
            }
            $row['refund'] = (int) round(((int) ($row['costo'] ?? 0)) / 2);
        }

        return $equipment;
    }

    private function getEquipmentAbilities(int $customizationId, bool $pet): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.testo_abilita,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_abilita\' and t.nome_campo=\'testo_abilita\' and t.lingua=\'en\' and t.fk_collegamento=a.id_abilita) as testo_abilita_en,
                    a.tipologia,
                    ae.aumento
             FROM ct_abilita_equipaggiamento ae
             INNER JOIN ct_abilita a ON a.id_abilita = ae.fk_abilita
             WHERE ae.fk_personalizzazione = :p AND a.equipet = :e'
        );
        $stmt->execute(['p' => $customizationId, 'e' => $pet ? 1 : 0]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function applyPetBonusDelta(int $studentId, int $petId, bool $add): void
    {
        if ($petId <= 0) {
            return;
        }

        $bonusLife = $this->petBonus($petId, 'vita');
        $bonusMana = $this->petBonus($petId, 'mana');
        if ($bonusLife === 0 && $bonusMana === 0) {
            return;
        }

        if ($add) {
            Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET vite_massime = vite_massime + :v,
                     mana_massimo = mana_massimo + :m
                 WHERE id_studente = :id'
            )->execute(['v' => $bonusLife, 'm' => $bonusMana, 'id' => $studentId]);

            return;
        }

        Database::getConnection()->prepare(
            'UPDATE ct_studenti
             SET vite_massime = GREATEST(vite_massime - :v, 0),
                 mana_massimo = GREATEST(mana_massimo - :m, 0)
             WHERE id_studente = :id'
        )->execute(['v' => $bonusLife, 'm' => $bonusMana, 'id' => $studentId]);

        Database::getConnection()->prepare(
            'UPDATE ct_studenti
             SET vite = LEAST(vite, vite_massime),
                 mana = LEAST(mana, mana_massimo)
             WHERE id_studente = :id'
        )->execute(['id' => $studentId]);
    }

    private function petBonus(int $petId, string $type): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COALESCE(SUM(ae.aumento), 0)
             FROM ct_abilita_equipaggiamento ae
             INNER JOIN ct_abilita a ON a.id_abilita = ae.fk_abilita
             WHERE ae.fk_personalizzazione = :p AND a.tipologia = :t AND a.equipet = 1'
        );
        $stmt->execute(['p' => $petId, 't' => $type]);
        return (int) $stmt->fetchColumn();
    }

    private function applyEquipmentShield(int $studentId, int $equipmentId, bool $firstEquip): void
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ae.aumento
             FROM ct_abilita_equipaggiamento ae
             INNER JOIN ct_abilita a ON a.id_abilita = ae.fk_abilita
             WHERE ae.fk_personalizzazione = :p AND a.tipologia = "difesa" AND a.equipet = 0
             LIMIT 1'
        );
        $stmt->execute(['p' => $equipmentId]);
        $defense = (int) ($stmt->fetchColumn() ?: 0);

        if ($defense <= 0) {
            return;
        }

        if ($firstEquip) {
            Database::getConnection()->prepare(
                'UPDATE ct_studenti SET scudi_massimi = :d, scudi = :d WHERE id_studente = :id'
            )->execute(['d' => $defense, 'id' => $studentId]);
            return;
        }

        Database::getConnection()->prepare(
            'UPDATE ct_studenti SET scudi_massimi = :d WHERE id_studente = :id'
        )->execute(['d' => $defense, 'id' => $studentId]);
    }

    private function resetStudentShield(int $studentId): void
    {
        Database::getConnection()->prepare('UPDATE ct_studenti SET scudi_massimi = 0, scudi = 0 WHERE id_studente = :id')
            ->execute(['id' => $studentId]);
    }

    private function normalizeImagePath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }

    private function error(string $message): array
    {
        return ['success' => false, 'message' => $message];
    }

    private function saveStudentCustomizationImage(array $file): string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            throw new \RuntimeException($this->t('student.customization.service.upload.image_required'));
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->t('student.customization.service.upload.upload_error'));
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > self::STUDENT_UPLOAD_MAX_BYTES) {
            throw new \RuntimeException($this->t('student.customization.service.upload.size_error'));
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->t('student.customization.service.upload.invalid_file'));
        }

        $info = getimagesize($tmpName);
        if ($info === false) {
            throw new \RuntimeException($this->t('student.customization.service.upload.invalid_file'));
        }

        $imageType = (int) ($info[2] ?? 0);
        $extension = match ($imageType) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_GIF => 'gif',
            default => '',
        };

        if ($extension === '') {
            throw new \RuntimeException($this->t('student.customization.service.upload.format_error'));
        }

        $relativeDir = '/assets/images/Personalizzazioni/Studenti';
        $absoluteDir = dirname(__DIR__, 2) . '/public' . $relativeDir;
        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->t('student.customization.service.upload.create_folder_failed'));
        }

        $fileName = 'student_' . date('Ymd_His') . '_' . substr(bin2hex(random_bytes(6)), 0, 12) . '.' . $extension;
        $absolutePath = $absoluteDir . '/' . $fileName;

        if (!$this->resizeAndCropUploadedImage($tmpName, $absolutePath, $imageType)) {
            throw new \RuntimeException($this->t('student.customization.service.upload.transform_error'));
        }

        return $relativeDir . '/' . $fileName;
    }

    private function resizeAndCropUploadedImage(string $sourcePath, string $destinationPath, int $imageType, int $targetWidth = 1080, int $targetHeight = 1080): bool
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException($this->t('student.customization.service.upload.gd_missing'));
        }

        $sourceSize = getimagesize($sourcePath);
        if ($sourceSize === false) {
            return false;
        }

        [$originalWidth, $originalHeight] = $sourceSize;
        if ((int) $originalWidth <= 0 || (int) $originalHeight <= 0) {
            return false;
        }

        $source = match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            default => false,
        };

        if (!$source) {
            return false;
        }

        $sourceAspect = $originalWidth / $originalHeight;
        $targetAspect = $targetWidth / $targetHeight;

        if ($sourceAspect > $targetAspect) {
            $cropHeight = $originalHeight;
            $cropWidth = (int) round($originalHeight * $targetAspect);
            $sourceX = (int) floor(($originalWidth - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $originalWidth;
            $cropHeight = (int) round($originalWidth / $targetAspect);
            $sourceX = 0;
            $sourceY = (int) floor(($originalHeight - $cropHeight) / 2);
        }

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        if (!$target) {
            imagedestroy($source);
            return false;
        }

        if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
            imagealphablending($target, false);
            imagesavealpha($target, true);
            $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
            imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        $resampled = imagecopyresampled($target, $source, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $cropWidth, $cropHeight);
        if (!$resampled) {
            imagedestroy($source);
            imagedestroy($target);
            return false;
        }

        $saved = match ($imageType) {
            IMAGETYPE_JPEG => imagejpeg($target, $destinationPath, 90),
            IMAGETYPE_PNG => imagepng($target, $destinationPath),
            IMAGETYPE_GIF => imagegif($target, $destinationPath),
            default => false,
        };

        imagedestroy($source);
        imagedestroy($target);

        return $saved;
    }

    private function getStudentFullName(int $studentId): string
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.nome, u.cognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return trim(((string) ($row['nome'] ?? '')) . ' ' . ((string) ($row['cognome'] ?? '')));
    }

    private function insertTeacherAlert(int $classId, string $text, string $type, string $link): void
    {
        Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, data_alert, letto, testo, tipologia, link, doc_stud, fk_studente)
             VALUES (:fk_classe, :data_alert, 0, :testo, :tipologia, :link, 0, 0)'
        )->execute([
            'fk_classe' => $classId,
            'data_alert' => date('Y-m-d H:i:s'),
            'testo' => $text,
            'tipologia' => $type,
            'link' => $link,
        ]);
    }

    private function deletePublicFile(string $relativePath): void
    {
        $relativePath = '/' . ltrim($relativePath, '/');
        $absolutePath = dirname(__DIR__, 2) . '/public' . $relativePath;
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
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
