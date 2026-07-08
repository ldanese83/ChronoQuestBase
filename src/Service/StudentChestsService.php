<?php

namespace App\Service;

use PDO;
use Throwable;

class StudentChestsService
{

    private StudentDashboardService $studentDashboardService;
    private TranslationService $translator;
    public function __construct()
    {
        $this->studentDashboardService = new StudentDashboardService();
        $this->translator = new TranslationService();
    }

    private const CHEST_RARITY_CARDS = [
        [
            'label' => 'comune',
            'title_key' => 'student.chests.rarity.common.title',
            'description_key' => 'student.chests.rarity.common.description',
            'img' => '/assets/images/Forzieri/forziere_comune.png',
            'class' => 'rarita-comune',
        ],
        [
            'label' => 'non comune',
            'title_key' => 'student.chests.rarity.uncommon.title',
            'description_key' => 'student.chests.rarity.uncommon.description',
            'img' => '/assets/images/Forzieri/forziere_non_comune.png',
            'class' => 'rarita-non-comune',
        ],
        [
            'label' => 'raro',
            'title_key' => 'student.chests.rarity.rare.title',
            'description_key' => 'student.chests.rarity.rare.description',
            'img' => '/assets/images/Forzieri/forziere_raro.png',
            'class' => 'rarita-raro',
        ],
        [
            'label' => 'epico',
            'title_key' => 'student.chests.rarity.epic.title',
            'description_key' => 'student.chests.rarity.epic.description',
            'img' => '/assets/images/Forzieri/forziere_epico.png',
            'class' => 'rarita-epico',
        ],
        [
            'label' => 'leggendario',
            'title_key' => 'student.chests.rarity.legendary.title',
            'description_key' => 'student.chests.rarity.legendary.description',
            'img' => '/assets/images/Forzieri/forziere_leggendario.png',
            'class' => 'rarita-leggendario',
        ],
    ];

    private const RARITY_LABEL_KEYS = [
        'comune' => 'student.chests.rarity.common.label',
        'non comune' => 'student.chests.rarity.uncommon.label',
        'raro' => 'student.chests.rarity.rare.label',
        'epico' => 'student.chests.rarity.epic.label',
        'leggendario' => 'student.chests.rarity.legendary.label',
    ];

    private const CHEST_CONFIG = [
        'comune' => ['coins' => [50, 80], 'xp' => [30, 50], 'cost_min' => 0, 'cost_max' => 300],
        'non comune' => ['coins' => [80, 150], 'xp' => [50, 100], 'cost_min' => 301, 'cost_max' => 600],
        'raro' => ['coins' => [150, 240], 'xp' => [100, 150], 'cost_min' => 601, 'cost_max' => 900],
        'epico' => ['coins' => [240, 400], 'xp' => [150, 200], 'cost_min' => 901, 'cost_max' => 1300],
        'leggendario' => ['coins' => [400, 600], 'xp' => [200, 500], 'cost_min' => 1301, 'cost_max' => 2000],
    ];

    public function getPageData(): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $studentId = (int) ($base['student']['id_studente'] ?? 0);
        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $base['raritaCards'] = $this->getRarityCards();
        $base['forzieri'] = $this->getOpenableChests($studentId);
        $base['contaforzieri'] = count($base['forzieri']);
        $base['nextChestDeliveriesMissing'] = $this->getNextChestDeliveriesMissing($studentId, $classId);
        $base['rewardModal'] = [
            'type' => (string) ($_GET['reward_type'] ?? ''),
            'amount' => (string) ($_GET['reward_amount'] ?? ''),
            'name' => (string) ($_GET['reward_name'] ?? ''),
            'img' => $this->normalizeAssetPath((string) ($_GET['reward_img'] ?? '')),
        ];

        return $base;
    }

    private function getNextChestDeliveriesMissing(int $studentId, int $classId): int
    {
        if ($studentId <= 0 || $classId <= 0) {
            return 3;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT esercizi_cons
             FROM ct_studenti_classi
             WHERE fk_studente = :fk_studente
               AND fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        $delivered = (int) ($stmt->fetchColumn() ?: 0);
        $progress = $delivered % 3;

        return $progress === 0 ? 3 : 3 - $progress;
    }

    public function openChest(array $input): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return ['query' => []];
        }

        $classId = (int) ($base['classroom']['id_classe'] ?? 0);
        $studentId = (int) ($base['student']['id_studente'] ?? 0);
        $chestId = (int) ($input['id_forziere'] ?? 0);

        if ($classId <= 0 || $studentId <= 0 || $chestId <= 0) {
            return ['query' => []];
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $chest = $this->getStudentChestForOpening($studentId, $chestId);
            if ($chest === null) {
                $pdo->rollBack();
                return ['query' => []];
            }

            $rarity = (string) ($chest['livello_rarita'] ?? 'comune');
            $rarityConfig = self::CHEST_CONFIG[$rarity] ?? self::CHEST_CONFIG['comune'];

            $rewardRoll = random_int(1, 100);
            $rewardType = 'monete';
            if ($rewardRoll > 80) {
                $rewardType = 'personalizzazione';
            } elseif ($rewardRoll > 40) {
                $rewardType = 'xp';
            }

            $rewardAmount = 0;
            $rewardName = '';
            $rewardImg = '';

            if ($rewardType === 'personalizzazione') {
                $customization = $this->drawCustomizationReward(
                    $studentId,
                    (int) $rarityConfig['cost_min'],
                    (int) $rarityConfig['cost_max']
                );

                if ($customization !== null) {
                    $rewardType = 'personalizzazione';
                    $rewardName = (string) ($customization['nome_personalizzazione'] ?? '');
                    $rewardImg = $this->normalizeAssetPath((string) ($customization['img'] ?? ''));
                } else {
                    $rewardType = 'monete';
                }
            }

            if ($rewardType === 'monete') {
                $rewardAmount = random_int((int) $rarityConfig['coins'][0], (int) $rarityConfig['coins'][1]);
                $pdo->prepare('UPDATE ct_studenti SET monete = monete + :reward WHERE id_studente = :id_studente')
                    ->execute([
                        'reward' => $rewardAmount,
                        'id_studente' => $studentId,
                    ]);
                $rewardImg = '/assets/images/Forzieri/monete.png';
            }

            if ($rewardType === 'xp') {
                $rewardAmount = random_int((int) $rarityConfig['xp'][0], (int) $rarityConfig['xp'][1]);
                $pdo->prepare('UPDATE ct_studenti SET xp = xp + :reward WHERE id_studente = :id_studente')
                    ->execute([
                        'reward' => $rewardAmount,
                        'id_studente' => $studentId,
                    ]);
                $rewardImg = '/assets/images/Forzieri/xp.png';
                 $this->studentDashboardService->handleLevelUpFromCurrentXp($studentId,$classId);
            }

            $pdo->prepare('UPDATE ct_forzieri_vinti SET aperto = 1 WHERE id_forziere = :id_forziere')
                ->execute(['id_forziere' => $chestId]);

            $pdo->commit();

            $query = ['reward_type' => $rewardType];
            if ($rewardAmount > 0) {
                $query['reward_amount'] = $rewardAmount;
            }
            if ($rewardName !== '') {
                $query['reward_name'] = $rewardName;
            }
            if ($rewardImg !== '') {
                $query['reward_img'] = $rewardImg;
            }

            return ['query' => $query];
        } catch (Throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return ['query' => []];
        }
    }

    private function baseData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
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
            'SELECT s.id_studente, s.livello, s.xp, u.nome, u.cognome
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

    private function getOpenableChests(int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_forziere, livello_rarita
             FROM ct_forzieri_vinti
             WHERE fk_studente = :fk_studente
               AND aperto = 0
             ORDER BY id_forziere DESC'
        );
        $stmt->execute(['fk_studente' => $studentId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(function (array $row): array {
            $rarity = (string) ($row['livello_rarita'] ?? 'comune');

            return [
                'id_forziere' => (int) ($row['id_forziere'] ?? 0),
                'livello_rarita' => $rarity,
                'rarity_label' => $this->translateRarity($rarity),
                'badge_class' => 'rarita-' . str_replace(' ', '-', $rarity),
                'img' => '/assets/images/Forzieri/forziere_' . str_replace(' ', '_', $rarity) . '.png',
            ];
        }, $rows);
    }

    private function getRarityCards(): array
    {
        return array_map(function (array $card): array {
            $label = (string) ($card['label'] ?? '');
            $card['label'] = $this->translateRarity($label);
            $card['titolo'] = $this->translator->translate((string) ($card['title_key'] ?? ''));
            $card['descrizione'] = $this->translator->translate((string) ($card['description_key'] ?? ''));

            return $card;
        }, self::CHEST_RARITY_CARDS);
    }

    private function translateRarity(string $rarity): string
    {
        $key = self::RARITY_LABEL_KEYS[$rarity] ?? '';

        return $key !== '' ? $this->translator->translate($key) : $rarity;
    }

    private function getStudentChestForOpening(int $studentId, int $chestId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_forziere, livello_rarita
             FROM ct_forzieri_vinti
             WHERE id_forziere = :id_forziere
               AND fk_studente = :fk_studente
               AND aperto = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_forziere' => $chestId,
            'fk_studente' => $studentId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function drawCustomizationReward(int $studentId, int $minCost, int $maxCost): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT p.id_personalizzazione, p.nome_personalizzazione, p.img
             FROM ct_personalizzazioni p
             WHERE p.id_personalizzazione NOT IN (
                SELECT sp.fk_personalizzazione
                FROM ct_studente_personalizzazioni sp
                WHERE sp.fk_studente = :fk_studente_existing
             )
               AND (
                    p.fk_personaggio = 0 OR p.fk_personaggio = (
                        SELECT s.fk_personaggio
                        FROM ct_studenti s
                        WHERE s.id_studente = :fk_studente_personaggio
                        LIMIT 1
                    )
               )
               AND p.costo BETWEEN :cost_min AND :cost_max
             ORDER BY RAND()'
        );

        $stmt->execute([
            'fk_studente_existing' => $studentId,
            'fk_studente_personaggio' => $studentId,
            'cost_min' => $minCost,
            'cost_max' => $maxCost,
        ]);

        $customizations = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        if ($customizations === []) {
            return null;
        }

        $winner = $customizations[array_rand($customizations)];

        Database::getConnection()->prepare(
            'INSERT INTO ct_studente_personalizzazioni (fk_studente, fk_personalizzazione, in_uso)
             VALUES (:fk_studente, :fk_personalizzazione, 0)'
        )->execute([
            'fk_studente' => $studentId,
            'fk_personalizzazione' => (int) ($winner['id_personalizzazione'] ?? 0),
        ]);

        return $winner;
    }

    /*public function handleLevelUp(int $studentId, int $classId): void
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, s.livello, s.xp
             FROM ct_studenti s
             WHERE s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($student === null) {
            return;
        }

        $currentLevel = (int) ($student['livello'] ?? 0);
        $xp = (int) ($student['xp'] ?? 0);

        $stmt = Database::getConnection()->prepare(
            'SELECT livello
             FROM ct_xp_livello
             WHERE xp_cumulata >= :xp
             ORDER BY xp_cumulata ASC
             LIMIT 1'
        );
        $stmt->execute(['xp' => $xp]);
        $nextRow = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($nextRow === null) {
            return;
        }

        $newLevel = max(0, (int) ($nextRow['livello'] ?? 1) - 1);
        if ($newLevel <= $currentLevel) {
            return;
        }

        $levelDiff = $newLevel - $currentLevel;
        Database::getConnection()->prepare(
            'UPDATE ct_studenti
             SET livello = :livello,
                 mana = LEAST(mana + (:mana_step * :level_diff), mana_massimo)
             WHERE id_studente = :id_studente'
        )->execute([
            'livello' => $newLevel,
            'mana_step' => 2,
            'level_diff' => $levelDiff,
            'id_studente' => $studentId,
        ]);

        $newPowers = 0;
        for ($lvl = $currentLevel + 1; $lvl <= $newLevel; $lvl++) {
            if ($this->levelUnlocksPower($lvl)) {
                $newPowers++;
            }
        }

        if ($newPowers > 0) {
            Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET pot_da_scegliere = pot_da_scegliere + :new_powers
                 WHERE id_studente = :id_studente'
            )->execute([
                'new_powers' => $newPowers,
                'id_studente' => $studentId,
            ]);
        }

        $manaRecovered = 2 * $levelDiff;
        $alertText = 'Il tuo personaggio è salito al livello ' . $newLevel . '! Si ricarica ' . $manaRecovered . ' mana.';
        if ($newPowers > 0) {
            $alertText .= ' <br />Puoi scegliere ' . $newPowers . ' nuovi poteri!';
        }

        Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, testo, fk_studente, data_alert, tipologia, link, letto, doc_stud)
             VALUES (:fk_classe, :testo, :fk_studente, :data_alert, :tipologia, :link, 0, 1)'
        )->execute([
            'fk_classe' => $classId,
            'testo' => $alertText,
            'fk_studente' => $studentId,
            'data_alert' => date('Y-m-d H:i:s'),
            'tipologia' => 'SaliLivello',
            'link' => 'classe_studente.php',
        ]);
    }

    private function levelUnlocksPower(int $level): bool
    {
        return in_array($level, [2, 6, 10, 14, 18, 22], true);
    }*/

    private function normalizeAssetPath(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        if (str_starts_with($trimmed, '/')) {
            return $trimmed;
        }

        $trimmed = preg_replace('#^(\./|\../)+#', '', $trimmed) ?? $trimmed;
        return '/' . ltrim($trimmed, '/');
    }
}
