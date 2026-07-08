<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\PluginManagerService;
use App\Service\Session;

class PluginPageController
{
    public function teacher(string $pluginCode): void
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', $permissionStatus === PermissionService::STATUS_NOT_LOGGED ? 'permission.nologin' : 'permission.noteacher');
            header('Location: /loginDoc');
            exit;
        }

        $classId = $permissionService->getCurrentClassId();
        $pluginManager = new PluginManagerService();
        if ($classId === null || !$pluginManager->isActive($pluginCode, $classId)) {
            Flash::add('danger', 'plugin.page.not_active');
            header('Location: /docenti/plugin');
            exit;
        }

        View::render('docenti/plugin/placeholder', [
            'title' => 'plugin.page.title',
            'plugin' => $pluginManager->getPluginByCode($pluginCode),
            'pluginCode' => $pluginCode,
            'useMathJax' => false,
        ], 'mainDocLayout');
    }

    public function student(string $pluginCode): void
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            header('Location: /studenti/dashboard');
            exit;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', $permissionStatus === PermissionService::STATUS_NOT_LOGGED ? 'permission.nologin' : 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        $classId = $permissionService->getCurrentClassId();
        $pluginManager = new PluginManagerService();
        if ($classId === null || !$pluginManager->isActive($pluginCode, $classId)) {
            Flash::add('danger', 'plugin.page.not_active');
            header('Location: /studenti/classe/dashboard');
            exit;
        }

        View::render('studenti/plugin/placeholder', [
            'title' => 'plugin.page.title',
            'plugin' => $pluginManager->getPluginByCode($pluginCode),
            'pluginCode' => $pluginCode,
            'useMathJax' => false,
        ], 'mainStudLayout');
    }
}
