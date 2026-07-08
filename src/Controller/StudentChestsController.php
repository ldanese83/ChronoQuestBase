<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentChestsService;

class StudentChestsController
{
    public function index(): void
    {
        $pageData = (new StudentChestsService())->getPageData();

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

        View::render('studenti/forzieri/index', array_merge($pageData, [
            'title' => 'student.chests.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/forzieri.css',
            ],
            'pageScripts' => [
                '/js/studenti/forzieri.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function openChest(): void
    {
        $result = (new StudentChestsService())->openChest($_POST);
        $query = http_build_query($result['query'] ?? []);
        $url = '/studenti/forzieri' . ($query !== '' ? ('?' . $query) : '');

        header('Location: ' . $url);
        exit;
    }
}
