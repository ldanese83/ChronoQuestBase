<?php

namespace App\Service;

use PDO;

class StudentNavbarCountersService
{
    public function getCounters(): array
    {
        $counters = [
            'contabadge' => 0,
            'contapowers' => 0,
            'contaesdasvol' => 0,
            'contapundasvol' => 0,
            'contaInvitiSquadra' => 0,
            'contaforzieri' => 0,
        ];

        $permissionService = new PermissionService();
        if ($permissionService->checkPermissionsStudent() !== PermissionService::STATUS_OK) {
            return $counters;
        }

        $userId = $permissionService->getCurrentUserId();
        $classId = $permissionService->getCurrentClassId();

        if ($userId === null || $classId === null) {
            return $counters;
        }

        $pdo = Database::getConnection();

        $counters['contabadge'] = $this->countRecentBadges($pdo, $userId);

        $student = $this->findStudentInClass($pdo, $userId, $classId);
        if ($student === null) {
            return $counters;
        }

        $studentId = (int) ($student['id_studente'] ?? 0);
        $counters['contapowers'] = (int) ($student['pot_da_scegliere'] ?? 0);

        $counters['contaesdasvol'] = $this->countExercisesToDo($pdo, $classId, $studentId);
        $counters['contapundasvol'] = $this->countPendingPunishments($pdo, $studentId);
        $counters['contaInvitiSquadra'] = $this->countTeamInvites($pdo, $classId, $studentId);
        $counters['contaforzieri'] = $this->countUnopenedChests($pdo, $studentId);

        return $counters;
    }

    private function countRecentBadges(PDO $pdo, int $userId): int
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_badge_alunni
             WHERE fk_utente = :fk_utente
               AND visto = 0
               AND data_conquista >= NOW() - INTERVAL 7 DAY'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    private function findStudentInClass(PDO $pdo, int $userId, int $classId): ?array
    {
        $stmt = $pdo->prepare(
            'SELECT s.id_studente, s.pot_da_scegliere
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.fk_utente = :fk_utente
               AND sc.fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'fk_classe' => $classId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function countExercisesToDo(PDO $pdo, int $classId, int $studentId): int
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_classi_esercizi_attivi cea
             WHERE cea.attivo = 1
               AND cea.fk_classe = :fk_classe
               AND cea.fk_esercizio NOT IN (
                   SELECT fk_esercizio
                   FROM ct_consegne_studenti
                   WHERE fk_studente = :fk_studente
               )'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function countPendingPunishments(PDO $pdo, int $studentId): int
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_studenti_punizioni
             WHERE fk_studente = :fk_studente
               AND completata = 0'
        );
        $stmt->execute(['fk_studente' => $studentId]);

        return (int) $stmt->fetchColumn();
    }

    private function countTeamInvites(PDO $pdo, int $classId, int $studentId): int
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_inviti_squadre i
             INNER JOIN ct_squadre s ON s.id_squadra = i.fk_squadra
             WHERE i.fk_studente = :fk_studente
               AND i.a_r = 0
               AND s.fk_classe = :fk_classe
               AND s.approvata = 1'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function countUnopenedChests(PDO $pdo, int $studentId): int
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_forzieri_vinti
             WHERE fk_studente = :fk_studente
               AND aperto = 0'
        );
        $stmt->execute(['fk_studente' => $studentId]);

        return (int) $stmt->fetchColumn();
    }
}
