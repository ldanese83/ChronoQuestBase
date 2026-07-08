<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentCustomizationService;

class StudentCustomizationController
{
    public function index(): void
    {
        $pageData = (new StudentCustomizationService())->getCustomizationPageData();
        $this->guard($pageData);

        View::render('studenti/personalizzazioni/index', array_merge($pageData, [
            'title' => 'student.customization.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/personalizza.css',
                '/css/negozio.css',
                '/css/equipaggiamento.css',
            ],
            'pageScripts' => [
                '/js/studenti/personalizzazioni.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function save(): void
    {
        $result = (new StudentCustomizationService())->saveCustomizationSelection($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni');
        exit;
    }

    public function sell(): void
    {
        $result = (new StudentCustomizationService())->sellCustomization($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni');
        exit;
    }

    public function shop(): void
    {
        $pageData = (new StudentCustomizationService())->getShopPageData();
        $this->guard($pageData);

        View::render('studenti/personalizzazioni/shop', array_merge($pageData, [
            'title' => 'student.customization.shop.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/personalizza.css',
                '/css/negozio.css',
                '/css/equipaggiamento.css',
            ],
            'pageScripts' => [
                '/js/studenti/negozio.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function buy(): void
    {
        $result = (new StudentCustomizationService())->buyCustomization($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni/negozio');
        exit;
    }

    public function uploadStudentCustomization(): void
    {
        $result = (new StudentCustomizationService())->uploadStudentCustomization($_POST, $_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni/negozio');
        exit;
    }

    public function equipment(): void
    {
        $pageData = (new StudentCustomizationService())->getEquipmentPageData();
        $this->guard($pageData);

        View::render('studenti/personalizzazioni/equipment', array_merge($pageData, [
            'title' => 'student.customization.equipment.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/personalizza.css',
                '/css/negozio.css',
                '/css/equipaggiamento.css',
            ],
            'pageScripts' => [
                '/js/studenti/equipaggiamento.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function buyEquipment(): void
    {
        $result = (new StudentCustomizationService())->buyEquipment($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni/equipaggiamento');
        exit;
    }

    public function equip(): void
    {
        $result = (new StudentCustomizationService())->equipEquipment($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni/equipaggiamento');
        exit;
    }

    public function sellEquipment(): void
    {
        $result = (new StudentCustomizationService())->sellEquipment($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'student.customization.operation.completed');
        header('Location: /studenti/personalizzazioni/equipaggiamento');
        exit;
    }

    private function guard(array $pageData): void
    {
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
    }
}
