<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\PluginManagerService;

class TeacherPluginsController
{
    public function index(): void
    {
        $service = new PluginManagerService();
        $pageData = $service->getManagementPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'permission.nologin');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'permission.noteacher');
            header('Location: /loginDoc');
            exit;
        }

        View::render('docenti/plugin/index', array_merge($pageData, [
            'title' => 'plugin.manage.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function saveClassPlugins(): void
    {
        $result = (new PluginManagerService())->saveClassPlugins($_POST);
        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'plugin.manage.error.save'));
        header('Location: /docenti/plugin');
        exit;
    }

    public function create(): void
    {
        $result = (new PluginManagerService())->createPlugin($_POST, $_FILES['plugin_package'] ?? []);
        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'plugin.manage.error.create'));
        header('Location: /docenti/plugin');
        exit;
    }
}
