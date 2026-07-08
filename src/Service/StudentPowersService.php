<?php

namespace App\Service;

use PDO;
use Throwable;

class StudentPowersService
{
    private const SELF_TARGET_POWER_IDS = [5, 7];
    private const MASS_EFFECT_POWER_IDS = [6, 8];
    private const PERMANENT_BOOST_POWER_IDS = [30, 31];
    private const POWER_TIERS = [
        'base' => ['label_key' => 'student.powers.tier.base', 'class' => 'bronze-box', 'badge_key' => 'student.powers.badge.base', 'rarity' => 'bronze', 'min' => 0, 'max' => 5],
        'intermediate' => ['label_key' => 'student.powers.tier.intermediate', 'class' => 'silver-box', 'badge_key' => 'student.powers.badge.intermediate', 'rarity' => 'silver', 'min' => 5, 'max' => 10],
        'advanced' => ['label_key' => 'student.powers.tier.advanced', 'class' => 'gold-box', 'badge_key' => 'student.powers.badge.advanced', 'rarity' => 'gold', 'min' => 10, 'max' => 15],
        'legendary' => ['label_key' => 'student.powers.tier.legendary', 'class' => 'ruby-shimmer', 'badge_key' => 'student.powers.badge.legendary', 'rarity' => 'ruby', 'min' => 15, 'max' => 20],
        'epic' => ['label_key' => 'student.powers.tier.epic', 'class' => 'emerald-shimmer', 'badge_key' => 'student.powers.badge.epic', 'rarity' => 'emerald', 'min' => 20, 'max' => null],
    ];

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPowersPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $studentId = (int) $base['student']['id_studente'];
        $classId = (int) $base['classroom']['id_classe'];

        $base['powers'] = $this->getStudentPowers($studentId, $classId);
        $base['allies'] = $this->getClassAllies($classId);

        return $base;
    }

    public function getAddPowerPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $studentId = (int) $base['student']['id_studente'];
        $classId = (int) $base['classroom']['id_classe'];

        $tiers = [];
        foreach (self::POWER_TIERS as $key => $meta) {
            $tiers[] = array_merge($meta, [
                'key' => $key,
                'label' => $this->translator->translate((string) $meta['label_key']),
                'badge' => $this->translator->translate((string) $meta['badge_key']),
                'powers' => $this->getAvailablePowersByTier(
                    $studentId,
                    $classId,
                    (int) $meta['min'],
                    is_int($meta['max']) ? $meta['max'] : null
                ),
            ]);
        }

        $base['availablePowerTiers'] = $tiers;
        $base['canChoosePower'] = (int) ($base['student']['pot_da_scegliere'] ?? 0) > 0;

        return $base;
    }

    public function usePower(array $input): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return [
                'success' => false,
                'message' => $this->translator->translate('student.powers.service.use.permission_denied'),
            ];
        }

        $classId = (int) $base['classroom']['id_classe'];
        $student = $base['student'];
        $studentId = (int) $student['id_studente'];
        $powerId = (int) ($input['power_id'] ?? 0);
        $targetStudentId = (int) ($input['target_student_id'] ?? 0);

        if ($powerId <= 0) {
            return $this->error($this->translator->translate('student.powers.service.power_invalid'));
        }

        $power = $this->getStudentAssignedPower($studentId, $classId, $powerId);
        if ($power === null) {
            return $this->error($this->translator->translate('student.powers.service.power_not_assigned'));
        }

        $currentMana = (int) ($student['mana'] ?? 0);
        $manaCost = (int) ($power['mana_necessario'] ?? 0);

        if ($currentMana < $manaCost) {
            return $this->error($this->translator->translate('student.powers.service.not_enough_mana'));
        }

        if (in_array($powerId, self::SELF_TARGET_POWER_IDS, true)) {
            if ($targetStudentId <= 0) {
                return $this->error($this->translator->translate('student.powers.service.target_required'));
            }

            if (!$this->isValidTargetForPower($classId, $studentId, $targetStudentId, $powerId)) {
                return $this->error($this->translator->translate('student.powers.service.target_invalid'));
            }
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $this->incrementPowerUsage($studentId, $powerId);
            $this->consumeMana($studentId, $manaCost);

            $targetName = null;
            if (in_array($powerId, self::SELF_TARGET_POWER_IDS, true)) {
                $targetName = $this->applySingleTargetEffect($targetStudentId, $powerId);
            }

            if (in_array($powerId, self::PERMANENT_BOOST_POWER_IDS, true)) {
                $this->applyPermanentBoostEffect($studentId, $powerId);
            }

            if (in_array($powerId, self::MASS_EFFECT_POWER_IDS, true)) {
                $this->applyMassEffect($classId, $studentId, $powerId);
            }

            $studentDisplay = trim(((string) ($student['nome'] ?? '')) . ' ' . ((string) ($student['cognome'] ?? '')));
            $powerName = (string) ($power['nome_potere'] ?? $this->translator->translate('student.powers.power'));

            if ($targetName !== null && $targetName !== '') {
                $teacherAlert = sprintf($this->translator->translate('student.powers.service.alert.teacher.used_target'), $studentDisplay, $powerName, $targetName);
                $studentAlert = sprintf($this->translator->translate('student.powers.service.alert.student.used_target'), $powerName, $targetName);
            } else {
                $teacherAlert = sprintf($this->translator->translate('student.powers.service.alert.teacher.used'), $studentDisplay, $powerName);
                $studentAlert = sprintf($this->translator->translate('student.powers.service.alert.student.used'), $powerName);
            }

            $this->insertAlert($classId, $studentId, $teacherAlert, 'UsatoPotere', '/docenti/poteri/assegnati', 0);
            $this->insertAlert($classId, $studentId, $studentAlert, 'UsatoPotere', '/studenti/poteri', 1);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $studentAlert,
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($this->translator->translate('student.powers.service.use.save_error'));
        }
    }

    public function choosePower(array $input): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->translator->translate('student.powers.service.choose.permission_denied'));
        }

        $studentId = (int) $base['student']['id_studente'];
        $classId = (int) $base['classroom']['id_classe'];
        $studentLevel = (int) ($base['student']['livello'] ?? 0);
        $remainingChoices = (int) ($base['student']['pot_da_scegliere'] ?? 0);
        $powerId = (int) ($input['power_id'] ?? 0);

        if ($remainingChoices <= 0) {
            return $this->error($this->translator->translate('student.powers.service.choose.none_pending'));
        }

        if ($powerId <= 0) {
            return $this->error($this->translator->translate('student.powers.service.power_invalid'));
        }

        $power = $this->findAvailablePowerForStudent($studentId, $classId, $powerId);
        if ($power === null) {
            return $this->error($this->translator->translate('student.powers.service.power_unavailable'));
        }

        if ((int) ($power['livello'] ?? 0) > $studentLevel) {
            return $this->error($this->translator->translate('student.powers.service.level_too_low'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'INSERT INTO ct_studenti_poteri (fk_potere, fk_studente, usato)
                 VALUES (:fk_potere, :fk_studente, 0)'
            );
            $stmt->execute([
                'fk_potere' => $powerId,
                'fk_studente' => $studentId,
            ]);

            $stmt = $pdo->prepare(
                'UPDATE ct_studenti
                 SET pot_da_scegliere = GREATEST(0, pot_da_scegliere - 1)
                 WHERE id_studente = :id_studente'
            );
            $stmt->execute(['id_studente' => $studentId]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('student.powers.service.choose.success'),
            ];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($this->translator->translate('student.powers.service.choose.save_error'));
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
            'powers' => [],
            'allies' => [],
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
                    s.fk_personaggio,
                    s.livello,
                    s.vite,
                    s.vite_massime,
                    s.scudi,
                    s.scudi_massimi,
                    s.mana,
                    s.mana_massimo,
                    s.pot_da_scegliere,
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

    private function getStudentPowers(int $studentId, int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_potere,
                    p.nome_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'nome_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                    p.descrizione_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'descrizione_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                    p.img_potere,
                    p.mana_necessario,
                    sp.usato
             FROM ct_studenti_poteri sp
             INNER JOIN ct_poteri p ON p.id_potere = sp.fk_potere
             WHERE sp.fk_studente = :fk_studente
               AND p.fk_classe = :fk_classe
             ORDER BY p.mana_necessario ASC, p.nome_potere ASC'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        $powers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($powers as &$power) {
            $power['img_src'] = $this->normalizeImagePath((string) ($power['img_potere'] ?? ''));
            $power['requires_target'] = in_array((int) $power['id_potere'], self::SELF_TARGET_POWER_IDS, true);
        }

        return $powers;
    }

    private function getClassAllies(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente,
                    s.fk_utente,
                    s.vite,
                    s.mana,
                    u.nome,
                    u.cognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :fk_classe
               AND s.fk_personaggio IS NOT NULL
             ORDER BY u.cognome ASC, u.nome ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAvailablePowersByTier(int $studentId, int $classId, int $minLevel, ?int $maxLevel): array
    {
        $levelCondition = 'p.livello > :min_level';
        $params = [
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
            'min_level' => $minLevel,
        ];

        if ($maxLevel !== null) {
            $levelCondition .= ' AND p.livello <= :max_level';
            $params['max_level'] = $maxLevel;
        }

        $stmt = Database::getConnection()->prepare(
            "SELECT p.id_potere,
                    p.nome_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella='ct_poteri' and t.nome_campo='nome_potere' and t.lingua='en' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                    p.descrizione_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella='ct_poteri' and t.nome_campo='descrizione_potere' and t.lingua='en' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                    p.img_potere,
                    p.mana_necessario,
                    p.livello
             FROM ct_poteri p
             WHERE (
                    p.fisso=1
                    OR p.fk_classe = :fk_classe
             )
               AND {$levelCondition}
               AND p.id_potere NOT IN (
                    SELECT sp.fk_potere
                    FROM ct_studenti_poteri sp
                    WHERE sp.fk_studente = :fk_studente
               )
             ORDER BY p.livello ASC, p.nome_potere ASC"
        );
        $stmt->execute($params);

        $powers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($powers as &$power) {
            $power['img_src'] = $this->normalizeImagePath((string) ($power['img_potere'] ?? ''));
        }

        return $powers;
    }

    private function findAvailablePowerForStudent(int $studentId, int $classId, int $powerId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_potere,
                    p.nome_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'nome_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                    p.descrizione_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'descrizione_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                    p.livello
             FROM ct_poteri p
             WHERE p.id_potere = :id_potere
               AND (
                    p.fisso=1
                    OR p.fk_classe = :fk_classe
               )
               AND p.id_potere NOT IN (
                    SELECT sp.fk_potere
                    FROM ct_studenti_poteri sp
                    WHERE sp.fk_studente = :fk_studente
               )
             LIMIT 1'
        );
        $stmt->execute([
            'id_potere' => $powerId,
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        $power = $stmt->fetch(PDO::FETCH_ASSOC);

        return $power ?: null;
    }

    private function getStudentAssignedPower(int $studentId, int $classId, int $powerId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_potere,
                    p.nome_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'nome_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as nome_potere_en,
                    p.descrizione_potere,
                    (select t.traduzione from ct_traduzioni t where t.nome_tabella=\'ct_poteri\' and t.nome_campo=\'descrizione_potere\' and t.lingua=\'en\' and t.fk_collegamento=p.id_potere) as descrizione_potere_en,
                    p.img_potere,
                    p.mana_necessario
             FROM ct_studenti_poteri sp
             INNER JOIN ct_poteri p ON p.id_potere = sp.fk_potere
             WHERE sp.fk_studente = :fk_studente
               AND p.fk_classe = :fk_classe
               AND p.id_potere = :id_potere
             LIMIT 1'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
            'id_potere' => $powerId,
        ]);

        $power = $stmt->fetch(PDO::FETCH_ASSOC);

        return $power ?: null;
    }

    private function isValidTargetForPower(int $classId, int $currentStudentId, int $targetStudentId, int $powerId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :fk_classe
               AND s.fk_personaggio IS NOT NULL
               AND s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'id_studente' => $targetStudentId,
        ]);

        $valid = (int) ($stmt->fetchColumn() ?: 0) > 0;
        if (!$valid) {
            return false;
        }

        if ($powerId === 7 && $targetStudentId === $currentStudentId) {
            return false;
        }

        return true;
    }

    private function incrementPowerUsage(int $studentId, int $powerId): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_studenti_poteri
             SET usato = usato + 1
             WHERE fk_studente = :fk_studente
               AND fk_potere = :fk_potere'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_potere' => $powerId,
        ]);
    }

    private function consumeMana(int $studentId, int $manaCost): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_studenti
             SET mana = GREATEST(0, mana - :mana_cost)
             WHERE id_studente = :id_studente'
        );
        $stmt->execute([
            'mana_cost' => $manaCost,
            'id_studente' => $studentId,
        ]);
    }

    private function applySingleTargetEffect(int $targetStudentId, int $powerId): ?string
    {
        $target = $this->getStudentById($targetStudentId);
        if ($target === null) {
            return null;
        }

        if ($powerId === 5) {
            if ((int) ($target['vite'] ?? 0) >= (int) ($target['vite_massime'] ?? 0)) {
                $stmt = Database::getConnection()->prepare(
                    'UPDATE ct_studenti
                     SET scudi = LEAST(scudi_massimi, scudi + 1)
                     WHERE id_studente = :id_studente'
                );
                $stmt->execute(['id_studente' => $targetStudentId]);
            } else {
                $stmt = Database::getConnection()->prepare(
                    'UPDATE ct_studenti
                     SET vite = LEAST(vite_massime, vite + 1)
                     WHERE id_studente = :id_studente'
                );
                $stmt->execute(['id_studente' => $targetStudentId]);
            }
        }

        if ($powerId === 7) {
            $stmt = Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET mana = mana_massimo
                 WHERE id_studente = :id_studente'
            );
            $stmt->execute(['id_studente' => $targetStudentId]);
        }

        return trim(((string) ($target['nome'] ?? '')) . ' ' . ((string) ($target['cognome'] ?? '')));
    }

    private function applyPermanentBoostEffect(int $studentId, int $powerId): void
    {
        if ($powerId === 30) {
            $stmt = Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET vite_massime = vite_massime + 1
                 WHERE id_studente = :id_studente'
            );
            $stmt->execute(['id_studente' => $studentId]);
        }

        if ($powerId === 31) {
            $stmt = Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET mana_massimo = mana_massimo + 1
                 WHERE id_studente = :id_studente'
            );
            $stmt->execute(['id_studente' => $studentId]);
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_studenti_poteri
             WHERE fk_studente = :fk_studente
               AND fk_potere = :fk_potere'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_potere' => $powerId,
        ]);
    }

    private function applyMassEffect(int $classId, int $studentId, int $powerId): void
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente,
                    s.vite,
                    s.vite_massime
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :fk_classe
               AND s.id_studente <> :id_studente'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'id_studente' => $studentId,
        ]);
        $classmates = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($classmates as $classmate) {
            $classmateId = (int) $classmate['id_studente'];

            if ($powerId === 6) {
                if ((int) ($classmate['vite'] ?? 0) >= (int) ($classmate['vite_massime'] ?? 0)) {
                    $update = Database::getConnection()->prepare(
                        'UPDATE ct_studenti
                         SET scudi = LEAST(scudi_massimi, scudi + 2)
                         WHERE id_studente = :id_studente'
                    );
                    $update->execute(['id_studente' => $classmateId]);
                } else {
                    $update = Database::getConnection()->prepare(
                        'UPDATE ct_studenti
                         SET vite = LEAST(vite_massime, vite + 2)
                         WHERE id_studente = :id_studente'
                    );
                    $update->execute(['id_studente' => $classmateId]);
                }
            }

            if ($powerId === 8) {
                $update = Database::getConnection()->prepare(
                    'UPDATE ct_studenti
                     SET mana = LEAST(mana_massimo, mana + 2)
                     WHERE id_studente = :id_studente'
                );
                $update->execute(['id_studente' => $classmateId]);
            }
        }

        if ($powerId === 6) {
            $updateSelf = Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET vite = vite - 2
                 WHERE id_studente = :id_studente'
            );
            $updateSelf->execute(['id_studente' => $studentId]);

            $self = $this->getStudentById($studentId);
            if ($self !== null) {
                $this->insertAlert(
                    $classId,
                    $studentId,
                    $this->translator->translate('student.powers.service.alert.extreme_sacrifice_lost_lives'),
                    'PersoCuori',
                    '/studenti/classe/dashboard',
                    1
                );

                if ((int) ($self['vite'] ?? 0) <= 0) {
                    $this->insertAlert(
                        $classId,
                        $studentId,
                        $this->translator->translate('student.powers.service.alert.no_lives_death'),
                        'Morte',
                        '/studenti/classe/dashboard',
                        1
                    );

                    $revive = Database::getConnection()->prepare(
                        'UPDATE ct_studenti
                         SET vite = vite_massime
                         WHERE id_studente = :id_studente'
                    );
                    $revive->execute(['id_studente' => $studentId]);
                }
            }
        }
    }

    private function getStudentById(int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente,
                    s.vite,
                    s.vite_massime,
                    s.mana,
                    s.mana_massimo,
                    u.nome,
                    u.cognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        return $student ?: null;
    }

    private function insertAlert(
        int $classId,
        int $studentId,
        string $text,
        string $type,
        string $link,
        int $docStud
    ): void {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (
                fk_classe,
                testo,
                fk_studente,
                data_alert,
                tipologia,
                link,
                letto,
                doc_stud
             ) VALUES (
                :fk_classe,
                :testo,
                :fk_studente,
                NOW(),
                :tipologia,
                :link,
                0,
                :doc_stud
             )'
        );

        $stmt->execute([
            'fk_classe' => $classId,
            'testo' => $text,
            'fk_studente' => $studentId,
            'tipologia' => $type,
            'link' => $link,
            'doc_stud' => $docStud,
        ]);
    }

    private function normalizeImagePath(string $rawPath): string
    {
        $rawPath = trim($rawPath);
        if ($rawPath === '') {
            return '/assets/images/undraw_profile_2.svg';
        }

        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            return $rawPath;
        }

        $normalized = preg_replace('#^(\./|\.\./)+#', '', $rawPath);

        return '/' . ltrim((string) $normalized, '/');
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
