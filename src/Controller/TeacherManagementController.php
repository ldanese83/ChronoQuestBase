<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherManagementService;

class TeacherManagementController
{
    public function index(): void
    {
        $pageData = (new TeacherManagementService())->getTeacherListPageData();

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

        View::render('docenti/docenti/index', array_merge($pageData, [
            'title' => 'teacher.management.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/docenti.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function add(): void
    {
        $teacherId = isset($_POST['id_docente']) ? (int) $_POST['id_docente'] : 0;
        $result = (new TeacherManagementService())->addTeacherToClass($teacherId);

        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione non riuscita.'));
        header('Location: /docenti/docenti');
        exit;
    }
}
