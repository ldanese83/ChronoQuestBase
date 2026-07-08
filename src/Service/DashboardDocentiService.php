<?php

namespace App\Service;

use PDO;

// Service che prepara i dati della dashboard docenti partendo dal modello legacy.
class DashboardDocentiService
{
    private PermissionService $permissionService;
    private TranslationService $translator;

    public function __construct()
    {
        $this->permissionService = new PermissionService();
        $this->translator = new TranslationService();
    }

    private function tr(string $key): string
    {
        return $this->translator->translate($key);
    }

    // Restituisce tutti i dati necessari alla view della dashboard docenti.
    public function getDashboardData(): array
    {
        $permissionStatus = $this->permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'students' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $this->permissionService->getCurrentClassId();
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

        return $data;
    }

    // Recupera i dati descrittivi della classe attualmente selezionata.
    private function getClassroom(int $classId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
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

    // Replica i dati della tabella legacy degli studenti per la dashboard docenti.
    private function getStudentsForClass(int $classId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT s.id_studente,
                    u.nome,
                    u.cognome,
                    s.fk_personaggio,
                    s.vite,
                    s.vite_massime,
                    s.scudi,
                    s.scudi_massimi,
                    s.mana,
                    s.mana_massimo,
                    s.monete,
                    s.livello,
                    s.xp,
                    p.immagine,
                    p.img_senza_sfondo,
                    p.color,
                    p.bordercolor
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             LEFT JOIN ct_personaggi p ON p.id_personaggio = s.fk_personaggio
             WHERE sc.fk_classe = :id_classe
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['id_classe' => $classId]);

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($students === []) {
            return [];
        }

        $studentIds = array_map(static fn (array $student): int => (int) $student['id_studente'], $students);
        $customizationsByStudent = $this->getActiveCustomizationsByStudent($studentIds);
        $xpByLevel = $this->getXpRowsByLevel($students);

        return array_map(function (array $student) use ($customizationsByStudent, $xpByLevel): array {
            $studentId = (int) $student['id_studente'];

            return $this->mapStudentRow(
                $student,
                $customizationsByStudent[$studentId] ?? [],
                $xpByLevel
            );
        }, $students);
    }

    // Recupera le personalizzazioni attive (capelli, sfondo, personale) per gli studenti della classe.
    private function getActiveCustomizationsByStudent(array $studentIds): array
    {
        if ($studentIds === []) {
            return [];
        }

        $pdo = Database::getConnection();
        $placeholders = implode(', ', array_fill(0, count($studentIds), '?'));
        $sql =
            'SELECT sp.fk_studente, p.tipo, p.img, p.nome_personalizzazione
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.in_uso = 1
               AND p.tipo IN ("Capelli", "Sfondo", "Personale")
               AND sp.fk_studente IN (' . $placeholders . ')';

        $stmt = $pdo->prepare($sql);
        foreach ($studentIds as $index => $studentId) {
            $stmt->bindValue($index + 1, $studentId, PDO::PARAM_INT);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $customizations = [];

        foreach ($rows as $row) {
            $studentId = (int) $row['fk_studente'];
            $type = (string) $row['tipo'];
            $customizations[$studentId][$type] = $row;
        }

        return $customizations;
    }

    // Indicizza i dati XP del livello successivo per calcolare la progress bar della tabella.
    private function getXpRowsByLevel(array $students): array
    {
        $levels = [];
        foreach ($students as $student) {
            $levels[] = (int) $student['livello'] + 1;
        }

        $levels = array_values(array_unique(array_filter($levels, static fn (int $level): bool => $level > 0)));
        if ($levels === []) {
            return [];
        }

        $pdo = Database::getConnection();
        $placeholders = implode(', ', array_fill(0, count($levels), '?'));
        $stmt = $pdo->prepare(
            'SELECT livello, xp_cumulata, xp
             FROM ct_xp_livello
             WHERE livello IN (' . $placeholders . ')'
        );

        foreach ($levels as $index => $level) {
            $stmt->bindValue($index + 1, $level, PDO::PARAM_INT);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $indexedRows = [];

        foreach ($rows as $row) {
            $indexedRows[(int) $row['livello']] = $row;
        }

        return $indexedRows;
    }

    // Trasforma una riga raw del DB nel formato pronto per la view.
    private function mapStudentRow(array $student, array $customizations, array $xpByLevel): array
    {
        $hasCharacter = (int) $student['fk_personaggio'] > 0;

        $mapped = [
            'id' => (int) $student['id_studente'],
            'surname' => (string) $student['cognome'],
            'name' => (string) $student['nome'],
            'hasCharacter' => $hasCharacter,
            'lifeIcons' => [],
            'shieldIcons' => [],
            'manaIcons' => [],
            'coins' => 0,
            'level' => '//',
            'nextLevel' => [
                'percent' => 0,
                'label' => '//',
            ],
            'characterImage' => null,
        ];

        if (!$hasCharacter) {
            return $mapped;
        }

        $mapped['lifeIcons'] = $this->buildIcons(
            (int) $student['vite'],
            (int) $student['vite_massime'],
            'fas fa-heart fa-sm fa-fw mr-2',
            'text-red-900',
            'text-red-400'
        );
        $mapped['shieldIcons'] = $this->buildIcons(
            (int) $student['scudi'],
            (int) $student['scudi_massimi'],
            'fas fa-shield fa-sm fa-fw mr-2',
            'text-grey-900',
            'text-grey-400'
        );
        $mapped['manaIcons'] = $this->buildIcons(
            (int) $student['mana'],
            (int) $student['mana_massimo'],
            'fas fa-yin-yang fa-sm fa-fw mr-2',
            'text-blue-900',
            'text-blue-400'
        );
        $mapped['coins'] = (int) $student['monete'];
        $mapped['level'] = (int) $student['livello'];
        $mapped['nextLevel'] = $this->buildNextLevelData($student, $xpByLevel);
        $mapped['characterImage'] = $this->buildCharacterImage($student, $customizations);

        return $mapped;
    }

    // Costruisce la rappresentazione delle icone piene/vuote per vite e mana.
    private function buildIcons(
        int $current,
        int $maximum,
        string $baseClasses,
        string $fullColorClass,
        string $emptyColorClass
    ): array {
        $icons = [];

        for ($i = 1; $i <= $maximum; $i++) {
            $icons[] = [
                'classes' => trim($baseClasses . ' ' . ($current >= $i ? $fullColorClass : $emptyColorClass)),
            ];
        }

        return $icons;
    }

    // Replica il calcolo legacy della progress bar verso il livello successivo.
    private function buildNextLevelData(array $student, array $xpByLevel): array
    {
        $nextLevel = (int) $student['livello'] + 1;
        $xpRow = $xpByLevel[$nextLevel] ?? null;

        if ($xpRow === null || (int) $xpRow['xp'] <= 0) {
            return [
                'percent' => 100,
                'label' => 'MAX',
            ];
        }

        $requiredXp = (int) $xpRow['xp'];
        $cumulativeXp = (int) $xpRow['xp_cumulata'];
        $currentXp = (int) $student['xp'];

        if ((int) $student['livello'] > 1) {
            $xpMissing = $cumulativeXp - $currentXp;
            $currentLevelXp = $requiredXp - $xpMissing;
        } else {
            $currentLevelXp = $currentXp;
        }

        $currentLevelXp = max(0, min($requiredXp, $currentLevelXp));
        $percent = (int) floor(($currentLevelXp / $requiredXp) * 100);

        return [
            'percent' => $percent,
            'label' => $currentLevelXp . ' / ' . $requiredXp . ' XP',
        ];
    }

    // Determina quale immagine personaggio mostrare, incluse eventuali personalizzazioni attive.
    private function buildCharacterImage(array $student, array $customizations): array
    {
        $borderColor = $this->sanitizeCssColor((string) ($student['bordercolor'] ?? '#efefef'));
        $shadowColor = $this->sanitizeCssColor((string) ($student['color'] ?? 'gray'));
        $style = sprintf('border:1px solid %s; box-shadow: 2px 2px 4px 2px %s;', $borderColor, $shadowColor);

        $personal = $customizations['Personale'] ?? null;
        if ($personal !== null && !empty($personal['img'])) {
            return [
                'mode' => 'single',
                'src' => (string) $personal['img'],
                'style' => $style,
            ];
        }

        $background = $customizations['Sfondo']['img'] ?? null;
        $hair = $customizations['Capelli']['img'] ?? null;

        if ($background || $hair) {
            return [
                'mode' => 'layered',
                'baseSrc' => (string) ($student['img_senza_sfondo'] ?? ''),
                'backgroundSrc' => $background ? (string) $background : null,
                'hairSrc' => $hair ? (string) $hair : null,
                'style' => $style,
            ];
        }

        return [
            'mode' => 'single',
            'src' => (string) ($student['immagine'] ?? ''),
            'style' => $style,
        ];
    }

    // Riduce il rischio di inserire valori CSS inattesi provenienti dal DB.
    private function sanitizeCssColor(string $value): string
    {
        $sanitized = preg_replace('/[^#(),.% a-zA-Z0-9-]/', '', $value) ?? '';
        return $sanitized !== '' ? trim($sanitized) : '#efefef';
    }

    public function removeHeart(int $studentId, string $motivation): array
    {
        if ($studentId <= 0) {
            return ['status' => 'error', 'message' => 'Studente non valido'];
        }

        $student = $this->getStudentForCurrentClass($studentId);
        if ($student === null) {
            return ['status' => 'error', 'message' => 'Studente non trovato'];
        }

        $this->decreaseHeartOrShield($studentId, (int) ($student['scudi'] ?? 0));
        $this->insertAlert(
            $studentId,
            'Perdi una vita per la seguente motivazione: ' . trim($motivation),
            'PersoCuori'
        );

        $after = $this->getStudentForCurrentClass($studentId);
        if ($after !== null && (int) ($after['vite'] ?? 0) > 0) {
            return ['status' => 'ok', 'message' => 'Cuore tolto allo studente!'];
        }

        return [
            'status' => 'dead',
            'message' => $this->tr('teacher.dashboard.death_punishment.student_dead'),
            'deaths' => [
                $this->buildDeathPayload($studentId, (string) ($student['nome'] ?? ''), (string) ($student['cognome'] ?? '')),
            ],
        ];
    }

    public function removeHeartBulk(array $studentIds, string $motivation): array
    {
        if ($studentIds === []) {
            return ['status' => 'error', 'message' => 'Errore nell\'invio degli id degli studenti'];
        }

        $messages = [];
        $deaths = [];
        foreach ($studentIds as $studentId) {
            $studentId = (int) $studentId;
            if ($studentId <= 0) {
                continue;
            }

            $student = $this->getStudentForCurrentClass($studentId);
            if ($student === null) {
                continue;
            }

            $this->decreaseHeartOrShield($studentId, (int) ($student['scudi'] ?? 0));
            $this->insertAlert(
                $studentId,
                'Perdi una vita per la seguente motivazione: ' . trim($motivation),
                'PersoCuori'
            );

            $after = $this->getStudentForCurrentClass($studentId);
            if ($after !== null && (int) ($after['vite'] ?? 0) > 0) {
                $messages[] = 'Cuore tolto allo studente!';
                continue;
            }

            $deaths[] = $this->buildDeathPayload(
                $studentId,
                (string) ($student['nome'] ?? ''),
                (string) ($student['cognome'] ?? '')
            );
        }

        if ($deaths !== []) {
            return [
                'status' => 'dead',
                'message' => $this->tr('teacher.dashboard.death_punishment.some_students_dead'),
                'messages' => $messages,
                'deaths' => $deaths,
            ];
        }

        return ['status' => 'ok', 'message' => 'Cuore tolto agli studenti selezionati!', 'messages' => $messages];
    }

    public function instantDeath(int $studentId): array
    {
        if ($studentId <= 0) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.student.invalid')];
        }

        $student = $this->getStudentForCurrentClass($studentId);
        if ($student === null) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.student.not_found')];
        }

        $stmt = Database::getConnection()->prepare('UPDATE ct_studenti SET vite = 0 WHERE id_studente = :id');
        $stmt->execute(['id' => $studentId]);

        $this->insertAlert($studentId, $this->tr('teacher.dashboard.instant_death.student_alert'), 'MorteIstantanea');

        return [
            'status' => 'dead',
            'message' => $this->tr('teacher.dashboard.instant_death.registered'),
            'deaths' => [
                $this->buildDeathPayload($studentId, (string) ($student['nome'] ?? ''), (string) ($student['cognome'] ?? '')),
            ],
        ];
    }

    public function rewardBulk(array $studentIds, int $xp, int $coins, string $motivation): array
    {
        if ($studentIds === []) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.reward.no_students')];
        }
        if ($xp < 0 || $coins < 0) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.reward.non_negative_values')];
        }
        if ($xp === 0 && $coins === 0) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.reward.value_required')];
        }
        if (trim($motivation) === '') {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.reward.motivation_required')];
        }

        foreach ($studentIds as $studentId) {
            $studentId = (int) $studentId;
            if ($studentId <= 0 || $this->getStudentForCurrentClass($studentId) === null) {
                continue;
            }

            if ($xp > 0) {
                $stmtXp = Database::getConnection()->prepare(
                    'UPDATE ct_studenti SET xp = xp + :xp WHERE id_studente = :id_studente'
                );
                $stmtXp->execute(['xp' => $xp, 'id_studente' => $studentId]);
            }

            if ($coins > 0) {
                $stmtCoins = Database::getConnection()->prepare(
                    'UPDATE ct_studenti SET monete = monete + :monete WHERE id_studente = :id_studente'
                );
                $stmtCoins->execute(['monete' => $coins, 'id_studente' => $studentId]);
            }

            $parts = [];
            if ($xp > 0) {
                $parts[] = sprintf($this->tr('teacher.dashboard.reward.alert.xp'), $xp);
            }
            if ($coins > 0) {
                $parts[] = sprintf($this->tr('teacher.dashboard.reward.alert.coins'), $coins);
            }

            $alert = sprintf(
                $this->tr('teacher.dashboard.reward.alert.received'),
                implode(' ' . $this->tr('teacher.dashboard.reward.alert.and') . ' ', $parts),
                trim($motivation)
            );
            $this->insertAlert($studentId, $alert, 'Ricompensa');

            (new PluginEventBus())->dispatch(PluginEventBus::EVENT_STUDENT_REWARD_ASSIGNED, [
                'class_id' => $this->getCurrentClassId(),
                'student_id' => $studentId,
                'reward_type' => 'manual',
                'xp' => $xp,
                'coins' => $coins,
                'motivation' => trim($motivation),
                'source' => 'teacher.dashboard.reward_bulk',
            ]);
        }

        return ['status' => 'success', 'message' => $this->tr('teacher.dashboard.reward.assigned')];
    }

    public function assignDeathPunishment(array $payload, array $file): array
    {
        $studentId = (int) ($payload['id_studente'] ?? 0);
        $mode = (string) ($payload['modalita'] ?? 'random');
        $existingPunishmentId = (int) ($payload['id_punizione'] ?? 0);
        $newDescription = trim((string) ($payload['descrizione_punizione'] ?? ''));
        $days = (int) ($payload['giorni_per_consegna'] ?? 0);

        $student = $this->getStudentForCurrentClass($studentId);
        if ($studentId <= 0 || $student === null) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.student.invalid')];
        }

        $punishment = null;
        if ($mode === 'existing') {
            if ($existingPunishmentId <= 0) {
                return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.death_punishment.select_existing')];
            }
            $stmt = Database::getConnection()->prepare(
                'SELECT *
                 FROM ct_punizioni
                 WHERE id_punizione = :id
                   AND fk_classe = :fk_classe
                   AND id_punizione NOT IN (
                        SELECT fk_punizione FROM ct_studenti_punizioni WHERE fk_studente = :id_studente
                   )'
            );
            $stmt->execute([
                'id' => $existingPunishmentId,
                'fk_classe' => $this->getCurrentClassId(),
                'id_studente' => $studentId,
            ]);
            $punishment = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } elseif ($mode === 'new') {
            if ($newDescription === '' || $days <= 0) {
                return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.death_punishment.insert_new')];
            }

            $imagePath = null;
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK && !empty($file['tmp_name'])) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/punizioni';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }
                $safeName = uniqid('pun_', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', (string) $file['name']);
                $destination = $uploadDir . '/' . $safeName;
                if (move_uploaded_file((string) $file['tmp_name'], $destination)) {
                    $imagePath = '/uploads/punizioni/' . $safeName;
                }
            }

            $stmt = Database::getConnection()->prepare(
                'INSERT INTO ct_punizioni (descrizione_punizione, img_punizione, giorni_per_consegna, fk_classe)
                 VALUES (:descrizione, :img_punizione, :giorni, :fk_classe)'
            );
            $stmt->execute([
                'descrizione' => htmlspecialchars($newDescription, ENT_QUOTES, 'UTF-8'),
                'img_punizione' => $imagePath,
                'giorni' => $days,
                'fk_classe' => $this->getCurrentClassId(),
            ]);

            $punishment = [
                'id_punizione' => (int) Database::getConnection()->lastInsertId(),
                'descrizione_punizione' => htmlspecialchars($newDescription, ENT_QUOTES, 'UTF-8'),
                'giorni_per_consegna' => $days,
            ];
        } else {
            $punishment = $this->pickRandomPunishmentForStudent($studentId);
            if ($punishment === null) {
                return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.death_punishment.no_random_available')];
            }
        }

        if ($punishment === null) {
            return ['status' => 'error', 'message' => $this->tr('teacher.dashboard.death_punishment.assign_failed')];
        }

        $daysToDeliver = max(1, (int) ($punishment['giorni_per_consegna'] ?? 1));
        $dueDate = (new \DateTimeImmutable())->modify('+' . $daysToDeliver . ' days')->format('Y-m-d');

        $stmtAssign = Database::getConnection()->prepare(
            'INSERT INTO ct_studenti_punizioni (fk_studente, fk_punizione, link_consegna, completata, data_scadenza)
             VALUES (:fk_studente, :fk_punizione, "", 0, :data_scadenza)'
        );
        $stmtAssign->execute([
            'fk_studente' => $studentId,
            'fk_punizione' => (int) $punishment['id_punizione'],
            'data_scadenza' => $dueDate,
        ]);

        $plainDescription = html_entity_decode(strip_tags((string) ($punishment['descrizione_punizione'] ?? '')), ENT_QUOTES, 'UTF-8');
        $this->insertAlert(
            $studentId,
            $this->tr('teacher.dashboard.death_punishment.student_task_alert') . ' ' . $plainDescription,
            'Morte'
        );

        $stmtRevive = Database::getConnection()->prepare(
            'UPDATE ct_studenti SET vite = vite_massime WHERE id_studente = :id_studente'
        );
        $stmtRevive->execute(['id_studente' => $studentId]);

        return ['status' => 'ok', 'message' => $this->tr('teacher.dashboard.death_punishment.assigned')];
    }

    private function buildDeathPayload(int $studentId, string $name, string $surname): array
    {
        return [
            'id_studente' => $studentId,
            'nome' => $name,
            'cognome' => $surname,
            'punizioni' => $this->getAvailablePunishmentsForStudent($studentId),
        ];
    }

    private function getAvailablePunishmentsForStudent(int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_punizione, descrizione_punizione, giorni_per_consegna
             FROM ct_punizioni
             WHERE fk_classe = :fk_classe
               AND id_punizione NOT IN (
                    SELECT fk_punizione FROM ct_studenti_punizioni WHERE fk_studente = :fk_studente
               )
             ORDER BY descrizione_punizione'
        );
        $stmt->execute([
            'fk_classe' => $this->getCurrentClassId(),
            'fk_studente' => $studentId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(static function (array $row): array {
            $plain = html_entity_decode(strip_tags((string) ($row['descrizione_punizione'] ?? '')), ENT_QUOTES, 'UTF-8');
            $plain = preg_replace('/\s+/', ' ', $plain) ?? '';

            return [
                'id_punizione' => (int) ($row['id_punizione'] ?? 0),
                'descrizione_punizione' => mb_substr(trim($plain), 0, 120),
                'giorni_per_consegna' => (int) ($row['giorni_per_consegna'] ?? 0),
            ];
        }, $rows);
    }

    private function pickRandomPunishmentForStudent(int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_punizioni
             WHERE fk_classe = :fk_classe
               AND id_punizione NOT IN (
                    SELECT fk_punizione FROM ct_studenti_punizioni WHERE fk_studente = :fk_studente
               )
             ORDER BY RAND()
             LIMIT 1'
        );
        $stmt->execute([
            'fk_classe' => $this->getCurrentClassId(),
            'fk_studente' => $studentId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getStudentForCurrentClass(int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.*, u.nome, u.cognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.id_studente = :id_studente
               AND sc.fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_studente' => $studentId,
            'fk_classe' => $this->getCurrentClassId(),
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function decreaseHeartOrShield(int $studentId, int $shields): void
    {
        if ($shields > 0) {
            $stmt = Database::getConnection()->prepare(
                'UPDATE ct_studenti SET scudi = GREATEST(scudi - 1, 0) WHERE id_studente = :id_studente'
            );
            $stmt->execute(['id_studente' => $studentId]);
            return;
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_studenti SET vite = GREATEST(vite - 1, 0) WHERE id_studente = :id_studente'
        );
        $stmt->execute(['id_studente' => $studentId]);
    }

    private function insertAlert(int $studentId, string $text, string $type): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, testo, fk_studente, data_alert, tipologia, link, letto, doc_stud)
             VALUES (:fk_classe, :testo, :fk_studente, NOW(), :tipologia, :link, 0, 1)'
        );
        $stmt->execute([
            'fk_classe' => $this->getCurrentClassId(),
            'testo' => $text,
            'fk_studente' => $studentId,
            'tipologia' => $type,
            'link' => 'classe_studente.php',
        ]);
    }

    private function getCurrentClassId(): int
    {
        return (int) $this->permissionService->getCurrentClassId();
    }
}
