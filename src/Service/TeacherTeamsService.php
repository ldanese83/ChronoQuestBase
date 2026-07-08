<?php

namespace App\Service;

use PDO;
use Throwable;

class TeacherTeamsService
{
    private const MAX_TEAM_MEMBERS = 4;
    private const TEAM_TYPES = ['mercanti', 'maghi', 'guerrieri', 'saggi'];

    public function getTeamsPageData(): array
    {
        $translator = new TranslationService();
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'teams' => [],
            'classStudents' => [],
            'defaultEmblems' => [],
            'teamTypes' => self::TEAM_TYPES,
            'teamTypeLabels' => $this->getTeamTypeLabels($translator),
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

        $data['teams'] = $this->getTeamsForClass($classId);
        $data['classStudents'] = $this->getClassStudents($classId);
        $data['defaultEmblems'] = $this->getDefaultEmblems();

        return $data;
    }

    public function saveTeam(array $input, array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $translator = new TranslationService();
        $teamId = isset($input['id_squadra']) ? (int) $input['id_squadra'] : 0;
        $teamName = trim((string) ($input['nome_squadra'] ?? ''));
        $emblemMode = trim((string) ($input['emblema_tipo'] ?? 'keep'));
        $defaultEmblem = trim((string) ($input['default_emblema'] ?? ''));
        $membersRaw = (string) ($input['studenti'] ?? '[]');

        if ($teamName === '') {
            return $this->error($translator->translate('teacher.teams.error.name_required'));
        }
        $teamType = trim((string) ($input['tipologia'] ?? ''));
        if (!in_array($teamType, self::TEAM_TYPES, true)) {
            return $this->error($translator->translate('teacher.teams.error.invalid_type'));
        }

        $members = json_decode($membersRaw, true);
        if (!is_array($members)) {
            $members = [];
        }

        $members = array_values(array_unique(array_map(static fn ($value): int => (int) $value, $members)));
        $members = array_filter($members, static fn (int $id): bool => $id > 0);

        if (count($members) > self::MAX_TEAM_MEMBERS) {
            return $this->error($translator->translate('js.maxteam'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ($teamId > 0) {
                $team = $this->findTeamById($classId, $teamId);
                if ($team === null) {
                    throw new \RuntimeException($translator->translate('teacher.teams.error.not_found_in_class'));
                }

                if ((int) ($team['approvata'] ?? 0) !== 1) {
                    throw new \RuntimeException($translator->translate('teacher.teams.error.only_approved_editable'));
                }
            }

            $members = $this->filterAllowedMembers($classId, $members, $teamId > 0 ? $teamId : null);
            $emblemPath = $this->resolveEmblemPath($emblemMode, $defaultEmblem, $uploadedFile, $teamId === 0);

            if ($teamId === 0) {
                if ($emblemPath === null) {
                    throw new \RuntimeException($translator->translate('teacher.teams.error.select_emblem_for_create'));
                }

                $insert = $pdo->prepare(
                    'INSERT INTO ct_squadre (nome_squadra, emblema_squadra, fk_classe, tipologia, approvata, fk_creatore)
                     VALUES (:nome_squadra, :emblema_squadra, :fk_classe, :tipologia, 1, 0)'
                );
                $insert->execute([
                    'nome_squadra' => $teamName,
                    'emblema_squadra' => $emblemPath,
                    'fk_classe' => $classId,
                    'tipologia' => $teamType,
                ]);
                $teamId = (int) $pdo->lastInsertId();

                $this->syncMembers($teamId, $members);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $translator->translate('teacher.teams.created'),
                ];
            }

            if ($emblemPath !== null) {
                $update = $pdo->prepare(
                    'UPDATE ct_squadre
                         SET nome_squadra = :nome_squadra,
                         emblema_squadra = :emblema_squadra,
                         tipologia = :tipologia
                     WHERE id_squadra = :id_squadra
                       AND fk_classe = :fk_classe'
                );
                $update->execute([
                    'nome_squadra' => $teamName,
                    'emblema_squadra' => $emblemPath,
                    'tipologia' => $teamType,
                    'id_squadra' => $teamId,
                    'fk_classe' => $classId,
                ]);
            } else {
                $update = $pdo->prepare(
                    'UPDATE ct_squadre
                     SET nome_squadra = :nome_squadra,
                         tipologia = :tipologia
                     WHERE id_squadra = :id_squadra
                       AND fk_classe = :fk_classe'
                );
                $update->execute([
                    'nome_squadra' => $teamName,
                    'tipologia' => $teamType,
                    'id_squadra' => $teamId,
                    'fk_classe' => $classId,
                ]);
            }

            $this->syncMembers($teamId, $members);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $translator->translate('teacher.teams.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deleteTeam(int $teamId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($teamId <= 0) {
            return $this->error('Squadra non valida.');
        }

        $classId = $this->getCurrentClassIdOrFail();
        $team = $this->findTeamById($classId, $teamId);
        if ($team === null) {
            return $this->error('Team not found in the selected class.');
        }

        if ((int) ($team['approvata'] ?? 0) !== 1) {
            return $this->error('You can delete only approved teams');
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $deleteMembers = $pdo->prepare('DELETE FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra');
            $deleteMembers->execute(['fk_squadra' => $teamId]);

            $deleteInvites = $pdo->prepare('DELETE FROM ct_inviti_squadre WHERE fk_squadra = :fk_squadra');
            $deleteInvites->execute(['fk_squadra' => $teamId]);

            $deleteTeam = $pdo->prepare('DELETE FROM ct_squadre WHERE id_squadra = :id_squadra AND fk_classe = :fk_classe');
            $deleteTeam->execute([
                'id_squadra' => $teamId,
                'fk_classe' => $classId,
            ]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => 'Team deleted.',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function approveStudentTeam(int $teamId): array
    {
        return $this->changePendingTeamStatus($teamId, true);
    }

    public function rejectStudentTeam(int $teamId): array
    {
        return $this->changePendingTeamStatus($teamId, false);
    }

    private function changePendingTeamStatus(int $teamId, bool $approved): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($teamId <= 0) {
            return $this->error('Invalid team.');
        }

        $classId = $this->getCurrentClassIdOrFail();
        $team = $this->findPendingTeamWithCreator($classId, $teamId);
        if ($team === null) {
            return $this->error('Team not found or already approved.');
        }

        $pdo = Database::getConnection();
        $now = date('Y-m-d H:i:s');
        $translator = new TranslationService();
        $pdo->beginTransaction();
        try {
            if ($approved) {
                $update = $pdo->prepare('UPDATE ct_squadre SET approvata = 1 WHERE id_squadra = :id_squadra');
                $update->execute(['id_squadra' => $teamId]);

                $this->insertAlert(
                    $classId,
                    $translator->translate('teams.yourteam') . $team['nome_squadra'] . $translator->translate('teams.teacherapproved'),
                    (int) $team['fk_creatore'],
                    $now,
                    'SquadraApprovata'
                );

                $invitees = $this->getPendingInvitees($teamId);
                foreach ($invitees as $inviteeId) {
                    $this->insertAlert(
                        $classId,
                        $translator->translate('teams.invited') . $team['nome_squadra'] . '.',
                        $inviteeId,
                        $now,
                        'InvitoSquadra'
                    );
                }

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => 'Team approved.',
                ];
            }

            $this->insertAlert(
                $classId,
                $translator->translate('teams.yourteam') . $team['nome_squadra'] . $translator->translate('teams.notapproved'),
                (int) $team['fk_creatore'],
                $now,
                'SquadraRifiutata'
            );

            $deleteInvites = $pdo->prepare('DELETE FROM ct_inviti_squadre WHERE fk_squadra = :fk_squadra');
            $deleteInvites->execute(['fk_squadra' => $teamId]);

            $deleteMembers = $pdo->prepare('DELETE FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra');
            $deleteMembers->execute(['fk_squadra' => $teamId]);

            $deleteTeam = $pdo->prepare('DELETE FROM ct_squadre WHERE id_squadra = :id_squadra');
            $deleteTeam->execute(['id_squadra' => $teamId]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => 'Team rejected and deleted.',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

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

    private function getTeamsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_squadra,
                    s.nome_squadra,
                    s.tipologia,
                    s.emblema_squadra,
                    s.approvata,
                    s.fk_creatore,
                    creator_u.nome AS creatore_nome,
                    creator_u.cognome AS creatore_cognome,
                    GROUP_CONCAT(DISTINCT CONCAT(member_u.cognome, " ", member_u.nome) ORDER BY member_u.cognome, member_u.nome SEPARATOR ", ") AS membri,
                    GROUP_CONCAT(DISTINCT ss.fk_studente ORDER BY ss.fk_studente SEPARATOR ",") AS membro_ids
             FROM ct_squadre s
             LEFT JOIN ct_studenti creator_s ON creator_s.id_studente = s.fk_creatore
             LEFT JOIN ct_utenti creator_u ON creator_u.id_utente = creator_s.fk_utente
             LEFT JOIN ct_squadra_studente ss ON ss.fk_squadra = s.id_squadra
             LEFT JOIN ct_studenti member_s ON member_s.id_studente = ss.fk_studente
             LEFT JOIN ct_utenti member_u ON member_u.id_utente = member_s.fk_utente
             WHERE s.fk_classe = :fk_classe
             GROUP BY s.id_squadra
             ORDER BY s.approvata ASC, s.nome_squadra ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($teams as &$team) {
            $team['emblema_squadra'] = $this->normalizeAssetPath((string) ($team['emblema_squadra'] ?? ''));
            $team['tipologia'] = trim((string) ($team['tipologia'] ?? ''));
            if ((int) ($team['approvata'] ?? 0) === 0) {
                $team['invitati'] = $this->getPendingInviteeNames((int) $team['id_squadra']);
            } else {
                $team['invitati'] = [];
            }
        }
        unset($team);

        return $teams;
    }

    private function getClassStudents(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente,
                    CONCAT(u.cognome, " ", u.nome) AS nomecognome,
                    COALESCE(MAX(CASE WHEN sq.id_squadra IS NOT NULL THEN ss.fk_squadra END), 0) AS squadra_corrente
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             LEFT JOIN ct_squadra_studente ss ON ss.fk_studente = s.id_studente
             LEFT JOIN ct_squadre sq ON sq.id_squadra = ss.fk_squadra AND sq.fk_classe = :fk_classe
             WHERE sc.fk_classe = :fk_classe
             GROUP BY s.id_studente, u.cognome, u.nome
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_classe' => $classId]);

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

    private function filterAllowedMembers(int $classId, array $studentIds, ?int $teamId): array
    {
        if ($studentIds === []) {
            return [];
        }

        $pdo = Database::getConnection();
        $allowed = [];

        foreach (array_slice($studentIds, 0, self::MAX_TEAM_MEMBERS) as $studentId) {
            $classCheck = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_studenti_classi
                 WHERE fk_classe = :fk_classe
                   AND fk_studente = :fk_studente'
            );
            $classCheck->execute([
                'fk_classe' => $classId,
                'fk_studente' => $studentId,
            ]);

            if ((int) $classCheck->fetchColumn() !== 1) {
                continue;
            }

            $teamCheck = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_squadra_studente ss
                 INNER JOIN ct_squadre s ON s.id_squadra = ss.fk_squadra
                 WHERE ss.fk_studente = :fk_studente
                   AND s.fk_classe = :fk_classe
                   AND (:id_squadra IS NULL OR s.id_squadra <> :id_squadra)'
            );
            $teamCheck->execute([
                'fk_studente' => $studentId,
                'fk_classe' => $classId,
                'id_squadra' => $teamId,
            ]);

            if ((int) $teamCheck->fetchColumn() > 0) {
                continue;
            }

            $allowed[] = $studentId;
        }

        return $allowed;
    }

    private function resolveEmblemPath(string $mode, string $defaultEmblem, array $uploadedFile, bool $isCreate): ?string
    {
        $normalizedMode = in_array($mode, ['keep', 'upload', 'default'], true) ? $mode : 'keep';

        if ($normalizedMode === 'default') {
            $defaultName = basename($defaultEmblem);
            if ($defaultName === '') {
                throw new \RuntimeException('Select a valid default emblem.');
            }

            $fullPath = dirname(__DIR__, 2) . '/public/assets/images/Squadre/Default/' . $defaultName;
            if (!is_file($fullPath)) {
                throw new \RuntimeException('Default image is invalid.');
            }

            return '/assets/images/Squadre/Default/' . $defaultName;
        }

        if ($normalizedMode === 'upload') {
            if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                throw new \RuntimeException('Error on upload the team image.');
            }

            $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
            if ($tmpName === '' || !is_uploaded_file($tmpName)) {
                throw new \RuntimeException('Upload emblem is invalid.');
            }

            $originalName = (string) ($uploadedFile['name'] ?? 'emblema.png');
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
                throw new \RuntimeException('Emblem format not supported (only images allowed).');
            }

            $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Squadre';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName)) ?: 'emblema.' . $extension;
            $targetName = uniqid('team_', true) . '_' . $safeName;
            $targetPath = $targetDir . '/' . $targetName;

            if (!move_uploaded_file($tmpName, $targetPath)) {
                throw new \RuntimeException('Cannot save the uploaded emblem.');
            }

            return '/assets/images/Squadre/' . $targetName;
        }

        return $isCreate ? null : null;
    }

    private function syncMembers(int $teamId, array $memberIds): void
    {
        $pdo = Database::getConnection();

        $deleteStmt = $pdo->prepare('DELETE FROM ct_squadra_studente WHERE fk_squadra = :fk_squadra');
        $deleteStmt->execute(['fk_squadra' => $teamId]);

        if ($memberIds === []) {
            return;
        }

        $insertStmt = $pdo->prepare(
            'INSERT INTO ct_squadra_studente (fk_squadra, fk_studente)
             VALUES (:fk_squadra, :fk_studente)'
        );

        foreach (array_slice($memberIds, 0, self::MAX_TEAM_MEMBERS) as $memberId) {
            $insertStmt->execute([
                'fk_squadra' => $teamId,
                'fk_studente' => $memberId,
            ]);
        }
    }

    private function findTeamById(int $classId, int $teamId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_squadra, fk_classe, approvata
             FROM ct_squadre
             WHERE id_squadra = :id_squadra
               AND fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_squadra' => $teamId,
            'fk_classe' => $classId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function findPendingTeamWithCreator(int $classId, int $teamId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_squadra, s.nome_squadra, s.fk_creatore
             FROM ct_squadre s
             WHERE s.id_squadra = :id_squadra
               AND s.fk_classe = :fk_classe
               AND s.approvata = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_squadra' => $teamId,
            'fk_classe' => $classId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function getPendingInvitees(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT fk_studente
             FROM ct_inviti_squadre
             WHERE fk_squadra = :fk_squadra
               AND a_r = 0'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return array_map(static fn (array $row): int => (int) $row['fk_studente'], $rows);
    }

    private function getPendingInviteeNames(int $teamId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT CONCAT(u.cognome, " ", u.nome) AS nomecognome
             FROM ct_inviti_squadre i
             INNER JOIN ct_studenti s ON s.id_studente = i.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE i.fk_squadra = :fk_squadra
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_squadra' => $teamId]);

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [], 'nomecognome');
    }

    private function insertAlert(int $classId, string $text, int $studentId, string $date, string $type): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, testo, fk_studente, data_alert, tipologia, link, letto, doc_stud)
             VALUES (:fk_classe, :testo, :fk_studente, :data_alert, :tipologia, :link, 0, 1)'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'testo' => $text,
            'fk_studente' => $studentId,
            'data_alert' => $date,
            'tipologia' => $type,
            'link' => '/studenti/squadra',
        ]);
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

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $permission = $permissionService->checkPermissionsTeacher();

        if ($permission === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($permission) {
            PermissionService::STATUS_NOT_LOGGED => $this->error('Session is invalid. Go back to login'),
            PermissionService::STATUS_NOT_TEACHER => $this->error('You don\'t have the permission to modify teams of this class.'),
            PermissionService::STATUS_NO_CLASS => $this->error('No class selected.'),
            PermissionService::STATUS_NOT_CLASS_OWNER => $this->error('You cannot access the selected class.'),
            default => $this->error('Operation is invalid.'),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $class = Session::get('class');
        $classId = isset($class['id']) ? (int) $class['id'] : 0;
        if ($classId <= 0) {
            throw new \RuntimeException('No class selected.');
        }

        return $classId;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    private function getTeamTypeLabels(TranslationService $translator): array
    {
        return [
            'mercanti' => $translator->translate('teams.type.mercanti'),
            'maghi' => $translator->translate('teams.type.maghi'),
            'guerrieri' => $translator->translate('teams.type.guerrieri'),
            'saggi' => $translator->translate('teams.type.saggi'),
        ];
    }
}
