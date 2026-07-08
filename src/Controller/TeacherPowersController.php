<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherPowersService;

class TeacherPowersController
{
    public function index(): void
    {
        $service = new TeacherPowersService();
        $pageData = $service->getPowersPageData();

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

        View::render('docenti/poteri/index', array_merge($pageData, [
            'title' => 'teacher.powers.section.available',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/poteri.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function assigned(): void
    {
        $service = new TeacherPowersService();
        $pageData = $service->getAssignedPowersPageData($_GET);

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

        View::render('docenti/poteri/assigned', array_merge($pageData, [
            'title' => 'powers.students.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/poteri-assigned.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherPowersService())->savePower($_POST, $_FILES['power_image'] ?? []));
    }

    public function delete(string $powerId): void
    {
        $this->json((new TeacherPowersService())->deletePower((int) $powerId));
    }

    public function import(): void
    {
        $sourcePowerId = isset($_POST['source_power_id']) ? (int) $_POST['source_power_id'] : 0;
        $this->json((new TeacherPowersService())->importPower($sourcePowerId));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
