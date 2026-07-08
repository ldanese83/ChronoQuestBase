<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\TeacherProfileService;

class TeacherProfileController
{
    public function index(): void
    {
        $permissionStatus = (new PermissionService())->checkTeacherAreaAccess();

        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'permission.nologin');
            header('Location: /loginDoc');
            exit;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'permission.noteacher');
            header('Location: /loginDoc');
            exit;
        }

        $profile = (new TeacherProfileService())->getCurrentUserProfile();

        if ($profile === null) {
            Flash::add('danger', 'profile.user.not_found');
            header('Location: /docenti/classi');
            exit;
        }

        View::render('docenti/profilo/index', [
            'title' => 'profile.user.title',
            'useMathJax' => false,
            'profile' => $profile,
        ], 'mainDocLayout');
    }

    public function update(): void
    {
        $result = (new TeacherProfileService())->updateCurrentUserProfile([
            'email' => trim((string) ($_POST['email'] ?? '')),
            'receive_mail' => isset($_POST['receive_mail']) ? (int) $_POST['receive_mail'] : 0,
            'api_gemini' => trim((string) ($_POST['api_gemini'] ?? '')),
            'language' => trim((string) ($_POST['language'] ?? 'en')),
            'password' => (string) ($_POST['password'] ?? ''),
            'password_confirm' => (string) ($_POST['password_confirm'] ?? ''),
        ]);

        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'profile.save.error'));
        header('Location: /docenti/profilo');
        exit;
    }
}
