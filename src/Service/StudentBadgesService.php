<?php

namespace App\Service;

use PDO;

class StudentBadgesService
{
    public function getPageData(): array
    {
        $base = $this->baseData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $userId = (int) ($base['student']['id_utente'] ?? 0);

        $badges = $this->getStudentBadges($userId);
        $this->markBadgesAsSeen($userId);

        $base['badges'] = $badges;
        $base['contabadge'] = count($badges);

        return $base;
    }

    private function baseData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
            'badges' => [],
            'contabadge' => 0,
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
            'SELECT s.id_studente, s.fk_utente AS id_utente, u.nome, u.cognome
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

    private function getStudentBadges(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ba.id_badge_alunno,
                    ba.data_conquista,
                    b.id_badge,
                    b.nome_badge,
                    b.descrizione,
                    b.img_badge
             FROM ct_badge_alunni ba
             INNER JOIN ct_badge b ON b.id_badge = ba.fk_badge
             WHERE ba.fk_utente = :fk_utente
             ORDER BY ba.data_conquista DESC, ba.id_badge_alunno DESC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(function (array $badge): array {
            $rawDescription = (string) ($badge['descrizione'] ?? '');
            $description = trim(strip_tags(html_entity_decode($rawDescription, ENT_QUOTES | ENT_HTML5, 'UTF-8')));

            return [
                'id_badge_alunno' => (int) ($badge['id_badge_alunno'] ?? 0),
                'id_badge' => (int) ($badge['id_badge'] ?? 0),
                'nome_badge' => (string) ($badge['nome_badge'] ?? ''),
                'descrizione' => $description,
                'img_badge' => $this->normalizeAssetPath((string) ($badge['img_badge'] ?? '')),
                'data_conquista' => (string) ($badge['data_conquista'] ?? ''),
            ];
        }, $rows);
    }

    private function markBadgesAsSeen(int $userId): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_badge_alunni
             SET visto = 1
             WHERE fk_utente = :fk_utente'
        );

        $stmt->execute(['fk_utente' => $userId]);
    }

    private function normalizeAssetPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }
}
