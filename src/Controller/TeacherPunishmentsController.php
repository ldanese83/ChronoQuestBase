<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherPunishmentsService;

class TeacherPunishmentsController
{
    public function index(): void
    {
        $service = new TeacherPunishmentsService();
        $pageData = $service->getPunishmentsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        View::render('docenti/punizioni/index', array_merge($pageData, [
            'title' => 'teacher.punishments.section.available',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/punizioni.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function assigned(): void
    {
        $service = new TeacherPunishmentsService();
        $pageData = $service->getAssignedPunishmentsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        View::render('docenti/punizioni/assigned', array_merge($pageData, [
            'title' => 'teacher.punishments.assigned.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/punizioni-assigned.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherPunishmentsService())->savePunishment($_POST, $_FILES['punishment_image'] ?? []));
    }

    public function delete(string $punishmentId): void
    {
        $this->json((new TeacherPunishmentsService())->deletePunishment((int) $punishmentId));
    }

    public function import(): void
    {
        $sourcePunishmentId = isset($_POST['source_punishment_id']) ? (int) $_POST['source_punishment_id'] : 0;
        $this->json((new TeacherPunishmentsService())->importPunishment($sourcePunishmentId));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
