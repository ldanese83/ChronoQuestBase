<?php

namespace App\Service;

use PDO;
use Throwable;

class StudentTeamsService
{
    private const MAX_TEAM_MEMBERS = 4;
    private const MAX_INVITES = 3;
    private const TEAM_TYPES = [
        'mercanti' => [
            'label_key' => 'student.teams.type.merchants',
            'tooltip_key' => 'student.teams.type.merchants.tooltip',
        ],
        'saggi' => [
            'label_key' => 'student.teams.type.sages',
            'tooltip_key' => 'student.teams.type.sages.tooltip',
        ],
        'guerrieri' => [
            'label_key' => 'student.teams.type.warriors',
            'tooltip_key' => 'student.teams.type.warriors.tooltip',
        ],
        'maghi' => [
            'label_key' => 'student.teams.type.mages',
            'tooltip_key' => 'student.teams.type.mages.tooltip',
        ],
    ];

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getPageData(): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $classId = (int) $base['classroom']['id_classe'];
        $studentId = (int) $base['student']['id_studente'];

        $team = $this->getStudentTeam($studentId, $classId);
        $base['team'] = $this->decorateTeamType($team);
        $base['isCreator'] = $team !== null && (int) $team['fk_creatore'] === $studentId;
        $base['teamMembers'] = $team !== null ? $this->getTeamMembers((int) $team['id_squadra']) : [];
        $base['pendingInvites'] = $team === null ? array_map(fn (array $invite): array => $this->decorateTeamType($invite), $this->getPendingInvites($studentId, $classId)) : [];
        $base['rejectedInvites'] = $team !== null ? $this->getRejectedInvites((int) $team['id_squadra']) : [];
        $base['teamPendingInvitees'] = $team !== null ? $this->getTeamPendingInvitees((int) $team['id_squadra']) : [];
        $base['availableInvitees'] = $this->getAvailableInvitees($classId, $studentId, $team !== null ? (int) $team['id_squadra'] : null);
        $base['defaultEmblems'] = $this->getDefaultEmblems();
        $base['teamTypes'] = $this->getTranslatedTeamTypes();

        return $base;
    }

    public function saveTeam(array $input, array $uploadedFile): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.teams.service.manage_permission_denied'));
        }

        $classId = (int) $base['classroom']['id_classe'];
        $studentId = (int) $base['student']['id_studente'];
        $studentName = trim((string) $base['student']['nome'] . ' ' . (string) $base['student']['cognome']);

        $teamId = (int) ($input['id_squadra'] ?? 0);
        $teamName = trim((string) ($input['nome_squadra'] ?? ''));
        $teamType = trim((string) ($input['tipologia'] ?? ''));
        $invitees = $this->normalizeInvitees($input['studenti_invito'] ?? []);

        if ($teamName === '') {
            return $this->error($this->t('student.teams.service.name_required'));
        }

        if (!array_key_exists($teamType, self::TEAM_TYPES)) {
            return $this->error($this->t('student.teams.service.type_invalid'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $currentTeam = $this->getStudentTeam($studentId, $classId);
            if ($teamId === 0 && $currentTeam !== null) {
                throw new \RuntimeException($this->t('student.teams.service.already_in_team'));
            }

            if ($teamId === 0) {
                $emblemType = (string) ($input['emblema_tipo'] ?? '');
                $defaultEmblem = (string) ($input['emblema_default'] ?? '');
                $emblemPath = $this->resolveCreateEmblem($emblemType, $defaultEmblem, $uploadedFile);

                $stmt = $pdo->prepare(
                    'INSERT INTO ct_squadre (nome_squadra, emblema_squadra, fk_classe, tipologia, approvata, fk_creatore)
                     VALUES (:nome_squadra, :emblema_squadra, :fk_classe, :tipologia, 0, :fk_creatore)'
                );
                $stmt->execute([
                    'nome_squadra' => $teamName,
                    'emblema_squadra' => $emblemPath,
                    'fk_classe' => $classId,
                    'tipologia' => $teamType,
                    'fk_creatore' => $studentId,
                ]);

                $newTeamId = (int) $pdo->lastInsertId();
                $this->addTeamMember($newTeamId, $studentId);
                $invitees = $this->filterInvitees($classId, $invitees, $newTeamId);
                $this->createInvitesAndAlerts($classId, $newTeamId, $teamName, $studentName, $invitees);

                $this->insertAlert($classId, sprintf($this->t('student.teams.service.alert.created'), $studentName), 0, 'SquadreStudenti', '/docenti/squadre');

                $pdo->commit();
                return ['success' => true, 'message' => $this->t('student.teams.service.created')];
            }

            $creatorTeam = $this->getCreatorTeam($teamId, $classId, $studentId);
            if ($creatorTeam === null) {
                throw new \RuntimeException($this->t('student.teams.service.not_found_or_unauthorized'));
            }

            $stmt = $pdo->prepare(
                'UPDATE ct_squadre
                 SET nome_squadra = :nome_squadra,
                     tipologia = :tipologia
                 WHERE id_squadra = :id_squadra'
            );
            $stmt->execute([
                'nome_squadra' => $teamName,
                'tipologia' => $teamType,
                'id_squadra' => $teamId,
            ]);

            $memberCount = $this->countTeamMembers($teamId);
            $pendingCount = $this->countTeamPendingInvites($teamId);
            $availableSlots = max(0, self::MAX_TEAM_MEMBERS - $memberCount - $pendingCount);
            $invitees = array_slice($this->filterInvitees($classId, $invitees, $teamId), 0, $availableSlots);

            $this->createInvitesAndAlerts($classId, $teamId, $teamName, $studentName, $invitees);

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.teams.service.updated')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deleteTeam(int $teamId): array
    {
        if ($teamId <= 0) {
            return $this->error($this->t('student.teams.service.team_invalid'));
        }

        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.teams.service.delete_permission_denied'));
        }

        $classId = (int) $base['classroom']['id_classe'];
        $studentId = (int) $base['student']['id_studente'];
        $team = $this->getCreatorTeam($teamId, $classId, $studentId);

        if ($team === null) {
            return $this->error($this->t('student.teams.service.not_found_or_unauthorized'));
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra')->execute(['fk_squadra' => $teamId]);
            $pdo->prepare('DELETE FROM ct_inviti_squadre WHERE fk_squadra = :fk_squadra')->execute(['fk_squadra' => $teamId]);
            $pdo->prepare('DELETE FROM ct_squadre WHERE id_squadra = :id_squadra AND fk_classe = :fk_classe')->execute([
                'id_squadra' => $teamId,
                'fk_classe' => $classId,
            ]);
            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.teams.service.deleted')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }
    }

    public function leaveTeam(): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.teams.service.leave_permission_denied'));
        }

        $classId = (int) $base['classroom']['id_classe'];
        $studentId = (int) $base['student']['id_studente'];
        $team = $this->getStudentTeam($studentId, $classId);

        if ($team === null) {
            return $this->error($this->t('student.teams.service.not_in_team'));
        }

        if ((int) $team['fk_creatore'] === $studentId) {
            return $this->error($this->t('student.teams.service.creator_cannot_leave'));
        }

        Database::getConnection()->prepare(
            'DELETE FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra AND fk_studente = :fk_studente'
        )->execute([
            'fk_squadra' => (int) $team['id_squadra'],
            'fk_studente' => $studentId,
        ]);

        return ['success' => true, 'message' => $this->t('student.teams.service.left')];
    }

    public function handleInvite(int $inviteId, string $action): array
    {
        if ($inviteId <= 0 || !in_array($action, ['accetta', 'rifiuta'], true)) {
            return $this->error($this->t('student.teams.service.invalid_data'));
        }

        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $this->error($this->t('student.teams.service.invite_permission_denied'));
        }

        $classId = (int) $base['classroom']['id_classe'];
        $studentId = (int) $base['student']['id_studente'];
        $studentName = trim((string) $base['student']['nome'] . ' ' . (string) $base['student']['cognome']);

        $invite = $this->getInviteForStudent($inviteId, $studentId, $classId);
        if ($invite === null) {
            return $this->error($this->t('student.teams.service.invite_invalid'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ($action === 'accetta') {
                if ($this->getStudentTeam($studentId, $classId) !== null) {
                    throw new \RuntimeException($this->t('student.teams.service.already_in_team'));
                }

                if ($this->countTeamMembers((int) $invite['id_squadra']) >= self::MAX_TEAM_MEMBERS) {
                    throw new \RuntimeException($this->t('student.teams.service.max_members_reached'));
                }

                $this->addTeamMember((int) $invite['id_squadra'], $studentId);
                $pdo->prepare('UPDATE ct_inviti_squadre SET a_r = 1 WHERE id_invito = :id_invito')->execute(['id_invito' => $inviteId]);

                $this->insertAlert(
                    $classId,
                    sprintf($this->t('student.teams.service.alert.invite_accepted'), $studentName, (string) $invite['nome_squadra']),
                    (int) $invite['fk_creatore'],
                    'RispostaInvitoSquadra',
                    '/studenti/squadra'
                );

                $pdo->commit();
                return ['success' => true, 'message' => $this->t('student.teams.service.invite_accepted')];
            }

            $pdo->prepare('UPDATE ct_inviti_squadre SET a_r = 2 WHERE id_invito = :id_invito')->execute(['id_invito' => $inviteId]);
            $this->insertAlert(
                $classId,
                sprintf($this->t('student.teams.service.alert.invite_rejected'), $studentName, (string) $invite['nome_squadra']),
                (int) $invite['fk_creatore'],
                'RispostaInvitoSquadra',
                '/studenti/squadra'
            );

            $pdo->commit();
            return ['success' => true, 'message' => $this->t('student.teams.service.invite_rejected')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }
    }

    private function getTranslatedTeamTypes(): array
    {
        $types = [];
        foreach (self::TEAM_TYPES as $key => $info) {
            $types[$key] = [
                'label' => $this->t((string) ($info['label_key'] ?? '')),
                'tooltip' => $this->t((string) ($info['tooltip_key'] ?? '')),
            ];
        }

        return $types;
    }

    private function decorateTeamType(?array $team): ?array
    {
        if ($team === null) {
            return null;
        }

        $teamType = (string) ($team['tipologia'] ?? '');
        $teamTypes = $this->getTranslatedTeamTypes();
        if (isset($teamTypes[$teamType])) {
            $team['tipologia_label'] = $teamTypes[$teamType]['label'];
            $team['tipologia_tooltip'] = $teamTypes[$teamType]['tooltip'];
        }

        return $team;
    }

    private function baseData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
            'team' => null,
            'isCreator' => false,
            'teamMembers' => [],
            'pendingInvites' => [],
            'rejectedInvites' => [],
            'teamPendingInvitees' => [],
            'availableInvitees' => [],
            'defaultEmblems' => [],
            'teamTypes' => $this->getTranslatedTeamTypes(),
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
        $data['student'] = $this->getStudent($userId, $classId);

        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
        }

        if ($data['student'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
        }

        return $data;
    }

    private function getClassroom(int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, c.colore, c.icona, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
               AND c.eliminata = 0
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getStudent(int $userId, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, u.nome, u.cognome
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

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getStudentTeam(int $studentId, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.*
             FROM ct_squadre s
             INNER JOIN ct_squadra_studente ss ON ss.fk_squadra = s.id_squadra
             WHERE ss.fk_studente = :fk_studente
               AND s.fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        $team = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($team !== null) {
            $team['emblema_squadra'] = $this->normalizeAssetPath((string) ($team['emblema_squadra'] ?? ''));
        }

        return $team;
    }

    private function getTeamMembers(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.nome, u.cognome
             FROM ct_squadra_studente ss
             INNER JOIN ct_studenti st ON st.id_studente = ss.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = st.fk_utente
             WHERE ss.fk_squadra = :fk_squadra
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getPendingInvites(int $studentId, int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT i.id_invito, s.nome_squadra, s.tipologia, s.emblema_squadra, s.id_squadra, u.nome, u.cognome
             FROM ct_inviti_squadre i
             INNER JOIN ct_squadre s ON s.id_squadra = i.fk_squadra
             INNER JOIN ct_studenti st ON st.id_studente = s.fk_creatore
             INNER JOIN ct_utenti u ON u.id_utente = st.fk_utente
             WHERE i.fk_studente = :fk_studente
               AND i.a_r = 0
               AND s.fk_classe = :fk_classe
               AND s.approvata = 1
             ORDER BY s.nome_squadra'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($rows as &$row) {
            $row['emblema_squadra'] = $this->normalizeAssetPath((string) ($row['emblema_squadra'] ?? ''));
        }

        return $rows;
    }

    private function getTeamPendingInvitees(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.nome, u.cognome
             FROM ct_inviti_squadre i
             INNER JOIN ct_studenti s ON s.id_studente = i.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE i.fk_squadra = :fk_squadra
               AND i.a_r = 0
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getRejectedInvites(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.nome, u.cognome
             FROM ct_inviti_squadre i
             INNER JOIN ct_studenti s ON s.id_studente = i.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE i.fk_squadra = :fk_squadra
               AND i.a_r = 2
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAvailableInvitees(int $classId, int $studentId, ?int $teamId): array
    {
        $pdo = Database::getConnection();
        $params = [
            'fk_classe' => $classId,
            'current_student' => $studentId,
        ];

        $excludeInviteSql = '';
        if ($teamId !== null) {
            $excludeInviteSql = 'AND s.id_studente NOT IN (
                SELECT i.fk_studente
                FROM ct_inviti_squadre i
                WHERE i.fk_squadra = :fk_squadra
                  AND i.a_r IN (0,2)
            )';
            $params['fk_squadra'] = $teamId;
        }

        $stmt = $pdo->prepare(
            'SELECT s.id_studente, CONCAT(u.cognome, " ", u.nome) AS nomecognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :fk_classe
               AND s.id_studente <> :current_student
               AND s.id_studente NOT IN (
                    SELECT ss.fk_studente
                    FROM ct_squadra_studente ss
                    INNER JOIN ct_squadre sq ON sq.id_squadra = ss.fk_squadra
                    WHERE sq.fk_classe = :fk_classe
               )
               ' . $excludeInviteSql . '
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getDefaultEmblems(): array
    {
        $defaultDir = dirname(__DIR__, 2) . '/public/assets/images/Squadre/Default';
        if (!is_dir($defaultDir)) {
            return [];
        }

        $files = glob($defaultDir . '/*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE);
        if ($files === false) {
            return [];
        }

        $mapped = [];
        foreach ($files as $file) {
            $mapped[] = [
                'name' => basename($file),
                'path' => '/assets/images/Squadre/Default/' . basename($file),
            ];
        }

        usort($mapped, static fn (array $a, array $b): int => strcmp($a['name'], $b['name']));

        return $mapped;
    }

    private function resolveCreateEmblem(string $emblemType, string $defaultEmblem, array $uploadedFile): string
    {
        if ($emblemType === 'default') {
            $defaultName = basename($defaultEmblem);
            $fullPath = dirname(__DIR__, 2) . '/public/assets/images/Squadre/Default/' . $defaultName;
            if ($defaultName === '' || !is_file($fullPath)) {
                throw new \RuntimeException($this->t('student.teams.service.default_emblem_invalid'));
            }

            return '/assets/images/Squadre/Default/' . $defaultName;
        }

        if ($emblemType === 'upload') {
            if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                throw new \RuntimeException($this->t('student.teams.service.emblem_upload_error'));
            }

            $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
            if ($tmpName === '' || !is_uploaded_file($tmpName)) {
                throw new \RuntimeException($this->t('student.teams.service.emblem_upload_invalid'));
            }

            $originalName = (string) ($uploadedFile['name'] ?? 'emblema.png');
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
                throw new \RuntimeException($this->t('student.teams.service.emblem_format_unsupported'));
            }

            $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Squadre';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName)) ?: 'emblema.' . $extension;
            $targetName = uniqid('stud_team_', true) . '_' . $safeName;
            $targetPath = $targetDir . '/' . $targetName;

            if (!move_uploaded_file($tmpName, $targetPath)) {
                throw new \RuntimeException($this->t('student.teams.service.emblem_save_failed'));
            }

            return '/assets/images/Squadre/' . $targetName;
        }

        throw new \RuntimeException($this->t('student.teams.service.emblem_required'));
    }

    private function addTeamMember(int $teamId, int $studentId): void
    {
        Database::getConnection()->prepare(
            'INSERT INTO ct_squadra_studente (fk_squadra, fk_studente)
             VALUES (:fk_squadra, :fk_studente)'
        )->execute([
            'fk_squadra' => $teamId,
            'fk_studente' => $studentId,
        ]);
    }

    private function createInvitesAndAlerts(int $classId, int $teamId, string $teamName, string $creatorName, array $invitees): void
    {
        foreach ($invitees as $inviteeId) {
            $exists = Database::getConnection()->prepare(
                'SELECT COUNT(*)
                 FROM ct_inviti_squadre
                 WHERE fk_squadra = :fk_squadra
                   AND fk_studente = :fk_studente
                   AND a_r IN (0,2)'
            );
            $exists->execute([
                'fk_squadra' => $teamId,
                'fk_studente' => $inviteeId,
            ]);

            if ((int) $exists->fetchColumn() > 0) {
                continue;
            }

            Database::getConnection()->prepare(
                'INSERT INTO ct_inviti_squadre (fk_studente, fk_squadra, a_r)
                 VALUES (:fk_studente, :fk_squadra, 0)'
            )->execute([
                'fk_studente' => $inviteeId,
                'fk_squadra' => $teamId,
            ]);

            $this->insertAlert(
                $classId,
                sprintf($this->t('student.teams.service.alert.invited'), $teamName, $creatorName),
                $inviteeId,
                'InvitoSquadra',
                '/studenti/squadra'
            );
        }
    }

    private function filterInvitees(int $classId, array $invitees, int $teamId): array
    {
        $allowed = [];
        foreach (array_slice($invitees, 0, self::MAX_INVITES) as $inviteeId) {
            $inClass = Database::getConnection()->prepare(
                'SELECT COUNT(*)
                 FROM ct_studenti_classi
                 WHERE fk_classe = :fk_classe
                   AND fk_studente = :fk_studente'
            );
            $inClass->execute([
                'fk_classe' => $classId,
                'fk_studente' => $inviteeId,
            ]);

            if ((int) $inClass->fetchColumn() !== 1) {
                continue;
            }

            $inOtherTeam = Database::getConnection()->prepare(
                'SELECT COUNT(*)
                 FROM ct_squadra_studente ss
                 INNER JOIN ct_squadre s ON s.id_squadra = ss.fk_squadra
                 WHERE ss.fk_studente = :fk_studente
                   AND s.fk_classe = :fk_classe'
            );
            $inOtherTeam->execute([
                'fk_studente' => $inviteeId,
                'fk_classe' => $classId,
            ]);

            if ((int) $inOtherTeam->fetchColumn() > 0) {
                continue;
            }

            if ($inviteeId > 0) {
                $allowed[] = $inviteeId;
            }
        }

        return array_values(array_unique($allowed));
    }

    private function getCreatorTeam(int $teamId, int $classId, int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT *
             FROM ct_squadre
             WHERE id_squadra = :id_squadra
               AND fk_classe = :fk_classe
               AND fk_creatore = :fk_creatore
             LIMIT 1'
        );
        $stmt->execute([
            'id_squadra' => $teamId,
            'fk_classe' => $classId,
            'fk_creatore' => $studentId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function countTeamMembers(int $teamId): int
    {
        $stmt = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra');
        $stmt->execute(['fk_squadra' => $teamId]);

        return (int) $stmt->fetchColumn();
    }

    private function countTeamPendingInvites(int $teamId): int
    {
        $stmt = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_inviti_squadre WHERE fk_squadra = :fk_squadra AND a_r = 0');
        $stmt->execute(['fk_squadra' => $teamId]);

        return (int) $stmt->fetchColumn();
    }

    private function getInviteForStudent(int $inviteId, int $studentId, int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT i.id_invito, i.fk_squadra, s.id_squadra, s.nome_squadra, s.fk_creatore
             FROM ct_inviti_squadre i
             INNER JOIN ct_squadre s ON s.id_squadra = i.fk_squadra
             WHERE i.id_invito = :id_invito
               AND i.fk_studente = :fk_studente
               AND s.fk_classe = :fk_classe
               AND s.approvata = 1
             LIMIT 1'
        );
        $stmt->execute([
            'id_invito' => $inviteId,
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function insertAlert(int $classId, string $text, int $studentId, string $type, string $link): void
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
            'link' => $link,
        ]);
    }

    private function normalizeInvitees(mixed $invitees): array
    {
        if (!is_array($invitees)) {
            return [];
        }

        $normalized = array_map(static fn ($value): int => (int) $value, $invitees);
        $normalized = array_filter($normalized, static fn (int $id): bool => $id > 0);

        return array_values(array_unique($normalized));
    }

    private function normalizeAssetPath(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        if (str_starts_with($trimmed, '/')) {
            return $trimmed;
        }

        $trimmed = preg_replace('#^(\./|\.\./)+#', '', $trimmed) ?? $trimmed;
        return '/' . ltrim($trimmed, '/');
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
