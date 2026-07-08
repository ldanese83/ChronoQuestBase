<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentPunishmentsService;
use App\Service\TranslationService;

class StudentPunishmentsController
{
    public function index(): void
    {
        $pageData = (new StudentPunishmentsService())->getPunishmentsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            Flash::add('danger', 'permission.noclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'permission.notyourclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
            exit;
        }

        View::render('studenti/punizioni/index', array_merge($pageData, [
            'title' => 'student.punishments.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/punizioni.css',
                'https://unpkg.com/dropzone@5/dist/min/dropzone.min.css',
            ],
            'pageScripts' => [
                'https://unpkg.com/dropzone@5/dist/min/dropzone.min.js',
                '/js/studenti/punizioni.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function upload(): void
    {
        $result = (new StudentPunishmentsService())->uploadPunishmentDelivery($_POST, $_FILES['file'] ?? []);

        http_response_code(($result['success'] ?? false) ? 200 : 422);
        header('Content-Type: text/plain; charset=utf-8');
        echo (string) ($result['message'] ?? (new TranslationService())->translate('student.punishments.operation_completed'));
    }
}
