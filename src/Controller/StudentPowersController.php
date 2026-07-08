<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentPowersService;

class StudentPowersController
{
    public function index(): void
    {
        $pageData = (new StudentPowersService())->getPowersPageData();

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

        View::render('studenti/powers/index', array_merge($pageData, [
            'title' => 'student.powers.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/poteri.css',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function usePower(): void
    {
        $result = (new StudentPowersService())->usePower($_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'Operazione completata.');
        header('Location: /studenti/poteri');
        exit;
    }

    public function add(): void
    {
        $pageData = (new StudentPowersService())->getAddPowerPageData();

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

        View::render('studenti/powers/add', array_merge($pageData, [
            'title' => 'student.powers.add.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/poteri.css',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function choosePower(): void
    {
        $result = (new StudentPowersService())->choosePower($_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'Operazione completata.');
        header('Location: /studenti/poteri/aggiungi');
        exit;
    }
}
