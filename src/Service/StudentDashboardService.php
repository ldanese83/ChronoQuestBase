<?php

namespace App\Service;

use PDO;

// Service dedicato alla dashboard studenti: elenco classi disponibili e dati della pagina personaggio/classe.
class StudentDashboardService
{
    private const TEAM_POWER_REQUIRED_DELIVERIES = 5;

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    // Restituisce i dati necessari alla prima pagina studenti, dove si sceglie la classe di accesso.
    public function getSelectionPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkStudentAreaAccess();
        $userId = $permissionService->getCurrentUserId();

        return [
            'permissionStatus' => $permissionStatus,
            'classes' => ($permissionStatus === PermissionService::STATUS_OK && $userId !== null)
                ? $this->getStudentClasses($userId)
                : [],
            'selectedClassId' => $permissionService->getCurrentClassId(),
        ];
    }

    // Restituisce i dati completi della dashboard interna della classe attualmente scelta dallo studente.
    public function getClassDashboardData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
            'availableCharacters' => [],
            'selectedCharacter' => null,
            'hero' => null,
            'team' => null,
            'teammates' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK || $classId === null || $userId === null) {
            return $data;
        }

        $data['classroom'] = $this->getClassById($classId);
        $student = $this->getStudentProfileForClass($userId, $classId);
        $data['student'] = $student;

        if ($data['classroom'] === null || $student === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        if ((int) ($student['fk_personaggio'] ?? 0) <= 0) {
            $data['availableCharacters'] = $this->getAvailableCharacters($classId);
            return $data;
        }

        $selectedCharacter = $this->getSelectedCharacter((int) $student['fk_personaggio']);
        if ($selectedCharacter === null) {
            return $data;
        }

        $customizations = $this->getStudentCustomizations((int) $student['id_studente']);
        $equipment = $customizations['Equipaggiamento'] ?? null;
        $nextLevel = $this->getXpLevelData((int) $student['livello'] + 1);
        $team = $this->getTeamData((int) $student['id_studente']);

        $data['selectedCharacter'] = $selectedCharacter;
        $data['hero'] = $this->buildHeroData($student, $selectedCharacter, $customizations, $equipment, $nextLevel);
        $data['team'] = $team;
        $data['teammates'] = $team !== null
            ? $this->getTeammates((int) $student['id_studente'], (int) $team['id'])
            : [];

        return $data;
    }

    // Restituisce i dati della pagina "I miei compagni" in stile legacy classmates.php.
    public function getClassmatesPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'classmates' => [],
            'rivalTeams' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK || $classId === null || $userId === null) {
            return $data;
        }

        $data['classroom'] = $this->getClassById($classId);
        $student = $this->getStudentProfileForClass($userId, $classId);

        if ($data['classroom'] === null || $student === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $studentId = (int) ($student['id_studente'] ?? 0);
        if ($studentId <= 0) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $data['classmates'] = $this->getClassmates($classId, $studentId);
        $data['rivalTeams'] = $this->getRivalTeams($classId, $studentId);

        return $data;
    }

    // Salva il personaggio scelto per lo studente corrente nella classe selezionata.
    public function chooseCharacter(int $characterId): bool
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkPermissionsStudent() !== PermissionService::STATUS_OK) {
            return false;
        }

        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();
        if ($classId === null || $userId === null) {
            return false;
        }

        $student = $this->getStudentProfileForClass($userId, $classId);
        if ($student === null || (int) ($student['fk_personaggio'] ?? 0) > 0) {
            return false;
        }

        $character = $this->getCharacterByClass((int) $classId, $characterId);
        if ($character === null) {
            return false;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE ct_studenti
             SET fk_personaggio = :id_personaggio,
                 vite = COALESCE(NULLIF(vite, 0), :vita_iniziale),
                 vite_massime = COALESCE(NULLIF(vite_massime, 0), :vita_iniziale),
                 mana = COALESCE(NULLIF(mana, 0), :mana_iniziale),
                 mana_massimo = COALESCE(NULLIF(mana_massimo, 0), :mana_iniziale),
                 vite_ultima_visita = COALESCE(NULLIF(vite_ultima_visita, 0), :vita_iniziale)
             WHERE id_studente = :id_studente'
        );

        return $stmt->execute([
            'id_personaggio' => $characterId,
            'vita_iniziale' => (int) $character['vita_iniziale'],
            'mana_iniziale' => (int) $character['mana_iniziale'],
            'id_studente' => (int) $student['id_studente'],
        ]);
    }

    public function activateTeamPower(): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkPermissionsStudent() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->translator->translate('student.class_dashboard.team_power.permission_denied')];
        }

        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();
        if ($classId === null || $userId === null) {
            return ['success' => false, 'message' => $this->translator->translate('student.class_dashboard.team_power.class_not_selected')];
        }

        $student = $this->getStudentProfileForClass($userId, $classId);
        if ($student === null) {
            return ['success' => false, 'message' => $this->translator->translate('student.class_dashboard.team_power.student_not_found')];
        }

        $studentId = (int) ($student['id_studente'] ?? 0);
        if ($studentId <= 0) {
            return ['success' => false, 'message' => $this->translator->translate('student.class_dashboard.team_power.student_invalid')];
        }

        $team = $this->getStudentTeamInClass($studentId, (int) $classId);
        if ($team === null) {
            return ['success' => false, 'message' => $this->translator->translate('student.class_dashboard.team_power.not_in_team')];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $selfStatus = $this->getTeamMemberStatus((int) $team['id_squadra'], $studentId);
            if ($selfStatus === null) {
                throw new \RuntimeException($this->translator->translate('student.class_dashboard.team_power.member_not_found'));
            }

            if ((int) ($selfStatus['potere_attivato'] ?? 0) === 1) {
                throw new \RuntimeException($this->translator->translate('student.class_dashboard.team_power.already_activated'));
            }

            $members = $this->getTeamMembersForPower((int) $team['id_squadra']);
            if ($members === []) {
                throw new \RuntimeException($this->translator->translate('student.class_dashboard.team_power.team_invalid'));
            }

            foreach ($members as $member) {
                if ((int) ($member['tot_ese_consegnati'] ?? 0) < self::TEAM_POWER_REQUIRED_DELIVERIES) {
                    throw new \RuntimeException($this->translator->translate('student.class_dashboard.team_power.not_enough_deliveries'));
                }
            }

            $pdo->prepare(
                'UPDATE ct_squadra_studente
                 SET potere_attivato = 1
                 WHERE fk_squadra = :fk_squadra
                   AND fk_studente = :fk_studente'
            )->execute([
                'fk_squadra' => (int) $team['id_squadra'],
                'fk_studente' => $studentId,
            ]);

            $stmtRemaining = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_squadra_studente
                 WHERE fk_squadra = :fk_squadra
                   AND potere_attivato = 0'
            );
            $stmtRemaining->execute(['fk_squadra' => (int) $team['id_squadra']]);
            $remaining = (int) $stmtRemaining->fetchColumn();

            if ($remaining > 0) {
                $pdo->commit();
                return ['success' => true, 'message' => $this->translator->translate('student.class_dashboard.team_power.waiting_members')];
            }

            $sumLevels = 0;
            $memberIds = [];
            foreach ($members as $member) {
                $sumLevels += (int) ($member['livello'] ?? 0);
                $memberIds[] = (int) ($member['id_studente'] ?? 0);
            }

            $teamType = strtolower(trim((string) ($team['tipologia'] ?? '')));
            if ($teamType === 'mercanti') {
                $reward = 8 * $sumLevels;
                $stmt = $pdo->prepare('UPDATE ct_studenti SET monete = monete + :reward WHERE id_studente = :id_studente');
                foreach ($memberIds as $memberId) {
                    $stmt->execute(['reward' => $reward, 'id_studente' => $memberId]);
                    $this->insertStudentAlert(
                        (int) $classId,
                        $memberId,
                        sprintf($this->translator->translate('student.class_dashboard.team_power.alert.merchants'), $reward),
                        'PotereSquadra'
                    );
                }
            } elseif ($teamType === 'guerrieri') {
                $stmt = $pdo->prepare(
                    'UPDATE ct_studenti
                     SET vite = vite_massime,
                         scudi = scudi_massimi
                     WHERE id_studente = :id_studente'
                );
                foreach ($memberIds as $memberId) {
                    $stmt->execute(['id_studente' => $memberId]);
                    $this->insertStudentAlert(
                        (int) $classId,
                        $memberId,
                        $this->translator->translate('student.class_dashboard.team_power.alert.warriors'),
                        'PotereSquadra'
                    );
                }
            } elseif ($teamType === 'maghi') {
                $stmt = $pdo->prepare(
                    'UPDATE ct_studenti
                     SET mana = mana_massimo
                     WHERE id_studente = :id_studente'
                );
                foreach ($memberIds as $memberId) {
                    $stmt->execute(['id_studente' => $memberId]);
                    $this->insertStudentAlert(
                        (int) $classId,
                        $memberId,
                        $this->translator->translate('student.class_dashboard.team_power.alert.mages'),
                        'PotereSquadra'
                    );
                }
            } elseif ($teamType === 'saggi') {
                $reward = 10 * $sumLevels;
                $stmt = $pdo->prepare('UPDATE ct_studenti SET xp = xp + :reward WHERE id_studente = :id_studente');
                foreach ($memberIds as $memberId) {
                    $stmt->execute(['reward' => $reward, 'id_studente' => $memberId]);
                    $this->insertStudentAlert(
                        (int) $classId,
                        $memberId,
                        sprintf($this->translator->translate('student.class_dashboard.team_power.alert.sages'), $reward),
                        'PotereSquadra'
                    );
                    $this->handleLevelUpFromCurrentXp($memberId, (int) $classId);
                }
            } else {
                throw new \RuntimeException($this->translator->translate('student.class_dashboard.team_power.unsupported_type'));
            }

            $pdo->prepare(
                'UPDATE ct_squadra_studente
                 SET tot_ese_consegnati = GREATEST(0, tot_ese_consegnati - :required_deliveries),
                     potere_attivato = 0
                 WHERE fk_squadra = :fk_squadra'
            )->execute([
                'required_deliveries' => self::TEAM_POWER_REQUIRED_DELIVERIES,
                'fk_squadra' => (int) $team['id_squadra'],
            ]);

            $pdo->commit();
            return ['success' => true, 'message' => $this->translator->translate('student.class_dashboard.team_power.success')];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Elenca solo le classi effettivamente associate allo studente corrente tramite tabella ponte legacy.
    public function getStudentClasses(int $userId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT c.id_classe,
                    c.nome_classe,
                    c.colore,
                    c.icona,
                    a.anno_scolastico
             FROM ct_utenti u
             INNER JOIN ct_studenti s ON s.fk_utente = u.id_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_classi c ON c.id_classe = sc.fk_classe
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE u.id_utente = :id_utente
               AND c.eliminata = 0
             ORDER BY a.anno_scolastico DESC, c.nome_classe ASC'
        );
        $stmt->execute(['id_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // Valida la classe selezionata e la salva in sessione per l'uso nelle pagine interne studenti.
    public function selectClass(int $classId): bool
    {
        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();

        if ($userId === null || !$permissionService->isStudent($userId)) {
            return false;
        }

        $class = $this->getStudentClassById($userId, $classId);
        if ($class === null) {
            Session::set('class', null);
            return false;
        }

        Session::set('class', [
            'id' => (int) $class['id_classe'],
            'name' => $class['nome_classe'],
            'color' => $class['colore'],
            'icon' => $class['icona'],
            'school_year' => $class['anno_scolastico'],
        ]);

        return true;
    }

    // Recupera una singola classe dello studente corrente, utile sia per i controlli sia per la sessione.
    private function getStudentClassById(int $userId, int $classId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT c.id_classe,
                    c.nome_classe,
                    c.colore,
                    c.icona,
                    a.anno_scolastico
             FROM ct_utenti u
             INNER JOIN ct_studenti s ON s.fk_utente = u.id_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_classi c ON c.id_classe = sc.fk_classe
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE u.id_utente = :id_utente
               AND c.id_classe = :id_classe
               AND c.eliminata = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);

        $class = $stmt->fetch(PDO::FETCH_ASSOC);

        return $class ?: null;
    }

    // Recupera i dati descrittivi della classe selezionata per mostrarli nell'header della dashboard interna.
    private function getClassById(int $classId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT c.id_classe,
                    c.nome_classe,
                    c.colore,
                    c.icona,
                    a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
               AND c.eliminata = 0
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        $classroom = $stmt->fetch(PDO::FETCH_ASSOC);

        return $classroom ?: null;
    }

    // Recupera il profilo studente con tutti i campi utili alla pagina classe/personaggio.
    private function getStudentProfileForClass(int $userId, int $classId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT s.id_studente,
                    s.fk_personaggio,
                    s.monete,
                    s.livello,
                    s.xp,
                    s.vite,
                    s.vite_massime,
                    s.vite_ultima_visita,
                    s.mana,
                    s.mana_massimo,
                    s.scudi,
                    s.scudi_massimi,
                    u.nome,
                    u.cognome,
                    u.username
             FROM ct_utenti u
             INNER JOIN ct_studenti s ON s.fk_utente = u.id_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE u.id_utente = :id_utente
               AND sc.fk_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        return $student ?: null;
    }

    // Elenco dei personaggi disponibili per la classe corrente, usato nella schermata di prima scelta.
    private function getAvailableCharacters(int $classId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT id_personaggio,
                    nome_personaggio,
                    descrizione,
                    immagine,
                    color,
                    bordercolor,
                    vita_iniziale,
                    mana_iniziale
             FROM ct_personaggi
             WHERE fk_classe = :id_classe
             ORDER BY nome_personaggio'
        );
        $stmt->execute(['id_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // Recupera il personaggio selezionato dallo studente.
    private function getSelectedCharacter(int $characterId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT id_personaggio,
                    nome_personaggio,
                    descrizione,
                    immagine,
                    img_senza_sfondo,
                    color,
                    bordercolor
             FROM ct_personaggi
             WHERE id_personaggio = :id_personaggio
             LIMIT 1'
        );
        $stmt->execute(['id_personaggio' => $characterId]);

        $character = $stmt->fetch(PDO::FETCH_ASSOC);

        return $character ?: null;
    }

    // Verifica che il personaggio appartenga davvero alla classe selezionata.
    private function getCharacterByClass(int $classId, int $characterId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT id_personaggio,
                    vita_iniziale,
                    mana_iniziale
             FROM ct_personaggi
             WHERE fk_classe = :id_classe
               AND id_personaggio = :id_personaggio
             LIMIT 1'
        );
        $stmt->execute([
            'id_classe' => $classId,
            'id_personaggio' => $characterId,
        ]);

        $character = $stmt->fetch(PDO::FETCH_ASSOC);

        return $character ?: null;
    }

    // Recupera tutte le personalizzazioni attive dello studente indicizzate per tipo.
    private function getStudentCustomizations(int $studentId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT p.id_personalizzazione,
                    p.tipo,
                    p.img,
                    p.nome_personalizzazione,
                    p.suffisso_costume,
                    sp.fk_personalizzazione
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             WHERE sp.fk_studente = :id_studente
               AND sp.in_uso = 1'
        );
        $stmt->execute(['id_studente' => $studentId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $indexed = [];

        foreach ($rows as $row) {
            $indexed[(string) $row['tipo']] = $row;
        }

        return $indexed;
    }

    // Replica il calcolo legacy della progressione verso il livello successivo.
    private function getXpLevelData(int $level): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT livello, xp_cumulata, xp
             FROM ct_xp_livello
             WHERE livello = :livello
             LIMIT 1'
        );
        $stmt->execute(['livello' => $level]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Costruisce il payload hero pronto per la view con grafica equivalente alla pagina legacy.
    private function buildHeroData(
        array $student,
        array $character,
        array $customizations,
        ?array $equipment,
        ?array $nextLevel
    ): array {
        $xpCurrent = (int) $student['xp'];
        $xpPercent = 100;
        $xpLabel = 'MAX';

        if ($nextLevel !== null && (int) $nextLevel['xp'] > 0) {
            $requiredXp = (int) $nextLevel['xp'];
            $cumulativeXp = (int) $nextLevel['xp_cumulata'];
            if ((int) $student['livello'] > 1) {
                $xpMissing = $cumulativeXp - $xpCurrent;
                $currentLevelXp = $requiredXp - $xpMissing;
            } else {
                $currentLevelXp = $xpCurrent;
            }

            $currentLevelXp = max(0, min($requiredXp, $currentLevelXp));
            $xpPercent = (int) floor(($currentLevelXp / $requiredXp) * 100);
            $xpLabel = $currentLevelXp . ' / ' . $requiredXp . ' XP';
        }

        $avatar = $this->buildAvatarImage($character, $customizations);
        $costumeSuffix = (string) ($equipment['suffisso_costume'] ?? '_starter.png');

        return [
            'displayName' => strtoupper(trim((string) ($customizations['Personale']['nome_personalizzazione'] ?? $character['nome_personaggio']))),
            'playerName' => trim((string) $student['nome'] . ' ' . (string) $student['cognome']),
            'descriptionHtml' => htmlspecialchars_decode(html_entity_decode((string) $character['descrizione'])),
            'avatar' => $avatar,
            'backgroundImage' => $this->normalizeAssetPath((string) ($customizations['BigBackground']['img'] ?? '')),
            'petImage' => $this->normalizeAssetPath((string) ($customizations['Pet']['img'] ?? '')),
            'mainCharacterImage' => $this->normalizeAssetPath(
                '/assets/images/Personalizzazioni/Costumes/' . $character['nome_personaggio'] . $costumeSuffix
            ),
            'life' => [
                'current' => (int) $student['vite'],
                'maximum' => (int) $student['vite_massime'],
                'danger' => (int) $student['vite'] <= 2,
            ],
            'mana' => [
                'current' => (int) $student['mana'],
                'maximum' => (int) $student['mana_massimo'],
            ],
            'shield' => [
                'enabled' => $this->hasDefenseAbility($equipment),
                'current' => (int) $student['scudi'],
                'maximum' => (int) $student['scudi_massimi'],
            ],
            'level' => (int) $student['livello'],
            'coins' => (int) $student['monete'],
            'xpPercent' => $xpPercent,
            'xpLabel' => $xpLabel,
        ];
    }

    // Costruisce i dati avatar tenendo conto delle personalizzazioni attive in uso.
    private function buildAvatarImage(array $character, array $customizations): array
    {
        $style = sprintf(
            'border:1px solid %s;box-shadow: 2px 2px 4px 2px %s;',
            $this->sanitizeCssColor((string) ($character['bordercolor'] ?? '#efefef')),
            $this->sanitizeCssColor((string) ($character['color'] ?? 'gray'))
        );

        $personal = $customizations['Personale']['img'] ?? null;
        if (!empty($personal)) {
            return [
                'mode' => 'single',
                'src' => $this->normalizeAssetPath((string) $personal),
                'style' => $style,
            ];
        }

        $background = $customizations['Sfondo']['img'] ?? null;
        $hair = $customizations['Capelli']['img'] ?? null;
        if ($background || $hair) {
            return [
                'mode' => 'layered',
                'backgroundSrc' => $background ? $this->normalizeAssetPath((string) $background) : null,
                'baseSrc' => $this->normalizeAssetPath((string) ($character['img_senza_sfondo'] ?? '')),
                'hairSrc' => $hair ? $this->normalizeAssetPath((string) $hair) : null,
                'style' => $style,
            ];
        }

        return [
            'mode' => 'single',
            'src' => $this->normalizeAssetPath((string) ($character['immagine'] ?? '')),
            'style' => $style,
        ];
    }

    // Indica se l'equipaggiamento attivo abilita gli scudi come nel codice legacy.
    private function hasDefenseAbility(?array $equipment): bool
    {
        if ($equipment === null || empty($equipment['fk_personalizzazione'])) {
            return false;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_abilita_equipaggiamento ae
             INNER JOIN ct_abilita a ON a.id_abilita = ae.fk_abilita
             WHERE ae.fk_personalizzazione = :fk_personalizzazione
               AND a.tipologia = "difesa"
               AND a.equipet = 0'
        );
        $stmt->execute([
            'fk_personalizzazione' => (int) $equipment['fk_personalizzazione'],
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Recupera i dati della squadra dello studente e lo stato del potere di squadra.
    private function getTeamData(int $studentId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT s.id_squadra,
                    s.nome_squadra,
                    s.emblema_squadra,
                    s.tipologia
             FROM ct_squadre s
             INNER JOIN ct_squadra_studente ss ON ss.fk_squadra = s.id_squadra
             WHERE ss.fk_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$team) {
            return null;
        }

        $membersStmt = $pdo->prepare(
            'SELECT st.id_studente,
                    st.livello,
                    u.nome,
                    u.cognome,
                    ss.tot_ese_consegnati,
                    ss.potere_attivato
             FROM ct_squadra_studente ss
             INNER JOIN ct_studenti st ON ss.fk_studente = st.id_studente
             INNER JOIN ct_utenti u ON st.fk_utente = u.id_utente
             WHERE ss.fk_squadra = :id_squadra'
        );
        $membersStmt->execute(['id_squadra' => (int) $team['id_squadra']]);
        $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $allReached = true;
        $studentActivated = false;
        $missingInfo = [];

        foreach ($members as $member) {
            $missing = max(0, self::TEAM_POWER_REQUIRED_DELIVERIES - (int) $member['tot_ese_consegnati']);
            $fullName = trim((string) $member['nome'] . ' ' . (string) $member['cognome']);
            $missingInfo[] = sprintf($this->translator->translate('student.class_dashboard.team_power.missing_exercises'), $fullName, $missing);
            if ($missing > 0) {
                $allReached = false;
            }
            if ((int) $member['id_studente'] === $studentId && (int) $member['potere_attivato'] === 1) {
                $studentActivated = true;
            }
        }

        $description = match (strtolower(trim((string) ($team['tipologia'] ?? '')))) {
            'mercanti' => $this->translator->translate('student.class_dashboard.team_power.description.merchants'),
            'guerrieri' => $this->translator->translate('student.class_dashboard.team_power.description.warriors'),
            'maghi' => $this->translator->translate('student.class_dashboard.team_power.description.mages'),
            'saggi' => $this->translator->translate('student.class_dashboard.team_power.description.sages'),
            default => $this->translator->translate('student.class_dashboard.team_power.description.unavailable'),
        };

        $enabled = false;
        $label = $this->translator->translate('student.class_dashboard.team_power.activate_label');
        $tooltip = '';

        if (!$allReached) {
            $tooltip = $this->translator->translate('student.class_dashboard.team_power.requirements_tooltip') . "\n"
                . implode("\n", $missingInfo)
                . "\n" . $this->translator->translate('student.class_dashboard.team_power.power_label') . ': ' . $description;
        } elseif ($studentActivated) {
            $label = $this->translator->translate('student.class_dashboard.team_power.activated_label');
            $tooltip = $this->translator->translate('student.class_dashboard.team_power.already_activated');
        } else {
            $enabled = true;
        }

        return [
            'id' => (int) $team['id_squadra'],
            'name' => (string) $team['nome_squadra'],
            'type' => (string) ($team['tipologia'] ?? ''),
            'emblem' => $this->normalizeAssetPath((string) ($team['emblema_squadra'] ?? '')),
            'powerEnabled' => $enabled,
            'powerLabel' => $label,
            'powerTooltip' => $tooltip,
            'powerDescription' => $description,
        ];
    }

    private function getStudentTeamInClass(int $studentId, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_squadra, s.tipologia
             FROM ct_squadre s
             INNER JOIN ct_squadra_studente ss ON ss.fk_squadra = s.id_squadra
             WHERE ss.fk_studente = :id_studente
               AND s.fk_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_studente' => $studentId,
            'id_classe' => $classId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getTeamMemberStatus(int $teamId, int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT potere_attivato, tot_ese_consegnati
             FROM ct_squadra_studente
             WHERE fk_squadra = :fk_squadra
               AND fk_studente = :fk_studente
             LIMIT 1'
        );
        $stmt->execute([
            'fk_squadra' => $teamId,
            'fk_studente' => $studentId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getTeamMembersForPower(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT st.id_studente,
                    st.livello,
                    st.mana,
                    st.mana_massimo,
                    st.xp,
                    st.livello AS livello_attuale,
                    ss.tot_ese_consegnati
             FROM ct_squadra_studente ss
             INNER JOIN ct_studenti st ON st.id_studente = ss.fk_studente
             WHERE ss.fk_squadra = :fk_squadra'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
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
            'link' => '/studenti/classe/dashboard',
        ]);
    }

    public function handleLevelUpFromCurrentXp(int $studentId, int $classId): void
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_studente, livello, xp, mana, mana_massimo
             FROM ct_studenti
             WHERE id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($student === null) {
            return;
        }

        $currentLevel = (int) ($student['livello'] ?? 0);
        $stmt = Database::getConnection()->prepare(
            'SELECT livello
             FROM ct_xp_livello
             WHERE xp_cumulata >= :xp
             ORDER BY xp_cumulata ASC
             LIMIT 1'
        );
        $stmt->execute(['xp' => (int) ($student['xp'] ?? 0)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($row === null) {
            return;
        }

        $newLevel = max(0, (int) ($row['livello'] ?? 1) - 1);
        if ($newLevel <= $currentLevel) {
            return;
        }

        $manaStep = 2 + $this->getManaIncreaseFromEquipment($studentId);
        $levelDiff = $newLevel - $currentLevel;
        Database::getConnection()->prepare(
            'UPDATE ct_studenti
             SET livello = :livello,
                 mana = LEAST(mana + (:mana_step * :level_diff), mana_massimo)
             WHERE id_studente = :id_studente'
        )->execute([
            'livello' => $newLevel,
            'mana_step' => $manaStep,
            'level_diff' => $levelDiff,
            'id_studente' => $studentId,
        ]);

        $newPowers = 0;
        for ($checkLevel = $currentLevel + 1; $checkLevel <= $newLevel; $checkLevel++) {
            if ($this->levelUnlocksPower($checkLevel)) {
                $newPowers++;
            }
        }

        if ($newPowers > 0) {
            Database::getConnection()->prepare(
                'UPDATE ct_studenti
                 SET pot_da_scegliere = pot_da_scegliere + :nuovi_poteri
                 WHERE id_studente = :id_studente'
            )->execute([
                'nuovi_poteri' => $newPowers,
                'id_studente' => $studentId,
            ]);
        }

        $manaRecovered = $manaStep * $levelDiff;
        $alertText = sprintf($this->translator->translate('student.class_dashboard.level_up.alert'), $newLevel, $manaRecovered);
        if ($newPowers > 0) {
            $alertText .= ' <br />' . sprintf($this->translator->translate('student.class_dashboard.level_up.new_powers'), $newPowers);
        }

        $this->insertStudentAlert($classId, $studentId, $alertText, 'SaliLivello');
    }

    private function getManaIncreaseFromEquipment(int $studentId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COALESCE(MAX(ae.aumento), 0) AS aumento
             FROM ct_studente_personalizzazioni sp
             INNER JOIN ct_personalizzazioni p ON p.id_personalizzazione = sp.fk_personalizzazione
             INNER JOIN ct_abilita_equipaggiamento ae ON ae.fk_personalizzazione = p.id_personalizzazione
             INNER JOIN ct_abilita a ON a.id_abilita = ae.fk_abilita
             WHERE sp.fk_studente = :fk_studente
               AND sp.in_uso = 1
               AND p.tipo = "Equipaggiamento"
               AND a.tipologia = "mana"
               AND a.equipet = 0'
        );
        $stmt->execute(['fk_studente' => $studentId]);

        return max(0, (int) $stmt->fetchColumn());
    }

    private function levelUnlocksPower(int $level): bool
    {
        return in_array($level, [2, 6, 10, 14, 18, 22], true);
    }

    // Recupera i compagni di squadra con costume e pet come nella griglia legacy.
    private function getTeammates(int $studentId, int $teamId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT st.id_studente,
                    u.nome,
                    u.cognome,
                    p.nome_personaggio
             FROM ct_squadra_studente ss
             INNER JOIN ct_studenti st ON ss.fk_studente = st.id_studente
             INNER JOIN ct_utenti u ON st.fk_utente = u.id_utente
             INNER JOIN ct_personaggi p ON st.fk_personaggio = p.id_personaggio
             WHERE ss.fk_squadra = :id_squadra
               AND st.id_studente <> :id_studente'
        );
        $stmt->execute([
            'id_squadra' => $teamId,
            'id_studente' => $studentId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $mapped = [];

        foreach ($rows as $row) {
            $customizations = $this->getStudentCustomizations((int) $row['id_studente']);
            $suffix = (string) ($customizations['Equipaggiamento']['suffisso_costume'] ?? '_starter.png');
            $mapped[] = [
                'fullName' => trim((string) $row['nome'] . ' ' . (string) $row['cognome']),
                'image' => $this->normalizeAssetPath(
                    'assets/images/Personalizzazioni/Costumes/' . $row['nome_personaggio'] . $suffix
                ),
                'petImage' => $this->normalizeAssetPath((string) ($customizations['Pet']['img'] ?? '')),
            ];
        }

        return $mapped;
    }

    // Recupera i compagni di classe con avatar e statistiche in stile pagina legacy classmates.
    private function getClassmates(int $classId, int $studentId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT st.id_studente,
                    st.livello,
                    st.vite,
                    st.mana,
                    u.nome,
                    u.cognome,
                    p.nome_personaggio,
                    p.immagine,
                    p.img_senza_sfondo,
                    p.color,
                    p.bordercolor
             FROM ct_studenti st
             INNER JOIN ct_utenti u ON st.fk_utente = u.id_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = st.id_studente
             INNER JOIN ct_personaggi p ON st.fk_personaggio = p.id_personaggio
             WHERE sc.fk_classe = :id_classe
               AND st.id_studente <> :id_studente
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute([
            'id_classe' => $classId,
            'id_studente' => $studentId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $mapped = [];

        foreach ($rows as $row) {
            $customizations = $this->getStudentCustomizations((int) $row['id_studente']);
            $avatar = $this->buildAvatarImage($row, $customizations);
            $displayName = trim((string) ($customizations['Personale']['nome_personalizzazione'] ?? $row['nome_personaggio']));

            $mapped[] = [
                'fullName' => trim((string) $row['nome'] . ' ' . (string) $row['cognome']),
                'displayCharacterName' => $displayName,
                'level' => (int) $row['livello'],
                'life' => (int) $row['vite'],
                'mana' => (int) $row['mana'],
                'avatar' => $avatar,
            ];
        }

        return $mapped;
    }

    // Recupera tutte le squadre avversarie approvate della classe corrente, escludendo quella dello studente.
    private function getRivalTeams(int $classId, int $studentId): array
    {
        $pdo = Database::getConnection();

        $studentTeamStmt = $pdo->prepare(
            'SELECT fk_squadra
             FROM ct_squadra_studente
             WHERE fk_studente = :id_studente
             LIMIT 1'
        );
        $studentTeamStmt->execute(['id_studente' => $studentId]);
        $studentTeamId = (int) ($studentTeamStmt->fetchColumn() ?: 0);

        $query = 'SELECT id_squadra, nome_squadra, emblema_squadra
                  FROM ct_squadre
                  WHERE fk_classe = :id_classe
                    AND approvata = 1';
        $params = ['id_classe' => $classId];

        if ($studentTeamId > 0) {
            $query .= ' AND id_squadra <> :id_squadra_studente';
            $params['id_squadra_studente'] = $studentTeamId;
        }

        $query .= ' ORDER BY nome_squadra';
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $membersStmt = $pdo->prepare(
            'SELECT st.id_studente,
                    u.nome,
                    u.cognome,
                    p.nome_personaggio
             FROM ct_squadra_studente ss
             INNER JOIN ct_studenti st ON ss.fk_studente = st.id_studente
             INNER JOIN ct_utenti u ON st.fk_utente = u.id_utente
             LEFT JOIN ct_personaggi p ON st.fk_personaggio = p.id_personaggio
             WHERE ss.fk_squadra = :id_squadra
             ORDER BY u.cognome, u.nome'
        );

        $mappedTeams = [];
        foreach ($teams as $team) {
            $membersStmt->execute(['id_squadra' => (int) $team['id_squadra']]);
            $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $mappedMembers = [];

            foreach ($members as $member) {
                $customizations = $this->getStudentCustomizations((int) $member['id_studente']);
                $suffix = (string) ($customizations['Equipaggiamento']['suffisso_costume'] ?? '_starter.png');
                $characterName = trim((string) ($member['nome_personaggio'] ?? ''));

                $mappedMembers[] = [
                    'fullName' => trim((string) $member['nome'] . ' ' . (string) $member['cognome']),
                    'image' => $characterName !== ''
                        ? $this->normalizeAssetPath('assets/images/Personalizzazioni/Costumes/' . $characterName . $suffix)
                        : '',
                    'petImage' => $this->normalizeAssetPath((string) ($customizations['Pet']['img'] ?? '')),
                ];
            }

            $mappedTeams[] = [
                'name' => (string) $team['nome_squadra'],
                'emblem' => $this->normalizeAssetPath((string) ($team['emblema_squadra'] ?? '')),
                'members' => $mappedMembers,
            ];
        }

        return $mappedTeams;
    }

    // Normalizza un path asset legacy in un URL pubblico compatibile con il nuovo frontend.
    private function normalizeAssetPath(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        $trimmed = preg_replace('#^(\./|\.\./)+#', '', $trimmed) ?? $trimmed;
        return '/' . ltrim($trimmed, '/');
    }

    // Riduce il rischio di inserire valori CSS inattesi provenienti dal DB.
    private function sanitizeCssColor(string $value): string
    {
        $sanitized = preg_replace('/[^#(),.% a-zA-Z0-9-]/', '', $value) ?? '';
        return $sanitized !== '' ? trim($sanitized) : '#efefef';
    }
}
