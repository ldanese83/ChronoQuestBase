<?php

namespace App\Service;

use App\Core\Router;

class PluginRouteRegistrar
{
    private PluginManagerService $pluginManager;
    private PermissionService $permissionService;

    public function __construct(?PluginManagerService $pluginManager = null, ?PermissionService $permissionService = null)
    {
        $this->pluginManager = $pluginManager ?? new PluginManagerService();
        $this->permissionService = $permissionService ?? new PermissionService();
    }

    public function register(Router $router): void
    {
        foreach ($this->pluginManager->getRouteDefinitions() as $route) {
            $method = strtoupper((string) ($route['method'] ?? ''));
            $path = (string) ($route['path'] ?? '');
            $handler = (string) ($route['handler'] ?? '');

            if ($path === '' || $handler === '') {
                continue;
            }

            $callback = function (...$params) use ($route): void {
                $this->dispatchPluginRoute($route, $params);
            };

            if ($method === 'GET') {
                $router->get($path, $callback);
                continue;
            }

            if ($method === 'POST') {
                $router->post($path, $callback);
            }
        }
    }

    private function dispatchPluginRoute(array $route, array $params): void
    {
        $pluginCode = (string) ($route['plugin'] ?? '');
        $area = (string) ($route['area'] ?? $this->guessAreaFromPath((string) ($route['path'] ?? '')));

        if (!$this->canAccessPluginRoute($pluginCode, $area)) {
            return;
        }

        [$className, $methodName] = array_pad(explode('@', (string) ($route['handler'] ?? ''), 2), 2, '');
        if ($className === '' || $methodName === '' || !class_exists($className) || !method_exists($className, $methodName)) {
            http_response_code(404);
            echo 'Pagina plugin non trovata';
            return;
        }

        call_user_func_array([new $className(), $methodName], $params);
    }

    private function canAccessPluginRoute(string $pluginCode, string $area): bool
    {
        if ($area === 'teacher') {
            return $this->canAccessTeacherPluginRoute($pluginCode);
        }

        if ($area === 'student') {
            return $this->canAccessStudentPluginRoute($pluginCode);
        }

        return $this->pluginManager->isActive($pluginCode);
    }

    private function canAccessTeacherPluginRoute(string $pluginCode): bool
    {
        $permissionStatus = $this->permissionService->checkPermissionsTeacher();

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            return false;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            return false;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', $permissionStatus === PermissionService::STATUS_NOT_LOGGED ? 'permission.nologin' : 'permission.noteacher');
            header('Location: /loginDoc');
            return false;
        }

        $classId = $this->permissionService->getCurrentClassId();
        if ($classId === null || !$this->pluginManager->isActive($pluginCode, $classId)) {
            Flash::add('danger', 'plugin.page.not_active');
            header('Location: /docenti/plugin');
            return false;
        }

        return true;
    }

    private function canAccessStudentPluginRoute(string $pluginCode): bool
    {
        $permissionStatus = $this->permissionService->checkPermissionsStudent();

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            header('Location: /studenti/dashboard');
            return false;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', $permissionStatus === PermissionService::STATUS_NOT_LOGGED ? 'permission.nologin' : 'permission.nostudent');
            header('Location: /loginStud');
            return false;
        }

        $classId = $this->permissionService->getCurrentClassId();
        if ($classId === null || !$this->pluginManager->isActive($pluginCode, $classId)) {
            Flash::add('danger', 'plugin.page.not_active');
            header('Location: /studenti/classe/dashboard');
            return false;
        }

        return true;
    }

    private function guessAreaFromPath(string $path): string
    {
        if (str_starts_with($path, '/docenti/')) {
            return 'teacher';
        }

        if (str_starts_with($path, '/studenti/')) {
            return 'student';
        }

        return '';
    }
}
