<?php

namespace App\Controller;

use App\Core\View;
use App\Service\DashboardDocentiService;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;

// Controller dedicato alla dashboard dei docenti: selezione classe, visione dei personaggi selezionati dagli studenti.
class DashboardDocentiController
{
    // Mostra la dashboard docenti con tabella studenti e modali pagina-specifiche.
    public function showDashboardDocenti(): void
    {
        $dashboardData = (new DashboardDocentiService())->getDashboardData();

        if (($dashboardData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if (($dashboardData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        View::render('docenti/dashboardDocenti', array_merge($dashboardData, [
            'title' => 'docenti.dashboard',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/dashboardDocenti.js',
            ],
            'pageModals' => [
                'docenti/modals/dashboardDocentiModals',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function removeHeart(): void
    {
        $studentId = (int) ($_POST['id_studente'] ?? 0);
        $motivation = (string) ($_POST['motivazione'] ?? '');
        $this->json((new DashboardDocentiService())->removeHeart($studentId, $motivation));
    }

    public function removeHeartBulk(): void
    {
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids)) {
            $ids = [];
        }
        $motivation = (string) ($_POST['msg'] ?? '');

        $this->json((new DashboardDocentiService())->removeHeartBulk($ids, $motivation));
    }

    public function instantDeath(): void
    {
        $studentId = (int) ($_POST['id_studente'] ?? 0);
        $this->json((new DashboardDocentiService())->instantDeath($studentId));
    }

    public function rewardBulk(): void
    {
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids)) {
            $ids = [];
        }

        $xp = (int) ($_POST['xp'] ?? 0);
        $coins = (int) ($_POST['monete'] ?? 0);
        $motivation = (string) ($_POST['motivazione'] ?? '');

        $this->json((new DashboardDocentiService())->rewardBulk($ids, $xp, $coins, $motivation));
    }

    public function assignDeathPunishment(): void
    {
        $this->json((new DashboardDocentiService())->assignDeathPunishment($_POST, $_FILES['file'] ?? []));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
