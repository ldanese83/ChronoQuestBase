<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherMaterialsService;

class TeacherMaterialsController
{
    public function index(): void
    {
        $service = new TeacherMaterialsService();
        $pageData = $service->getMaterialsPageData();

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

        View::render('docenti/materiali/index', array_merge($pageData, [
            'title' => 'teacher.materials.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/materiali.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherMaterialsService())->saveMaterial($_POST, $_FILES['material_file'] ?? []));
    }

    public function delete(string $materialId): void
    {
        $this->json((new TeacherMaterialsService())->deleteMaterial((int) $materialId));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
