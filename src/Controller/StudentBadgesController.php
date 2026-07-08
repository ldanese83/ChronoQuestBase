<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentBadgesService;

class StudentBadgesController
{
    public function index(): void
    {
        $pageData = (new StudentBadgesService())->getPageData();

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

        View::render('studenti/badge/index', array_merge($pageData, [
            'title' => 'student.badges.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/student-badge.css',
            ],
            'pageScripts' => [
                '/js/studenti/badge.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }
}
