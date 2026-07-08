<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\StudentProfileService;

class StudentProfileController
{
    public function index(): void
    {
        $permissionStatus = (new PermissionService())->checkStudentAreaAccess();

        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'permission.nologin');
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        $profile = (new StudentProfileService())->getCurrentUserProfile();

        if ($profile === null) {
            Flash::add('danger', 'profile.user.not_found');
            header('Location: /studenti/dashboard');
            exit;
        }

        View::render('studenti/profilo/index', [
            'title' => 'profile.user.title',
            'useMathJax' => false,
            'profile' => $profile,
        ], 'mainStudLayout');
    }

    public function update(): void
    {
        $result = (new StudentProfileService())->updateCurrentUserProfile([
            'receive_mail' => isset($_POST['receive_mail']) ? (int) $_POST['receive_mail'] : 0,
            'language' => trim((string) ($_POST['language'] ?? 'en')),
            'password' => (string) ($_POST['password'] ?? ''),
            'password_confirm' => (string) ($_POST['password_confirm'] ?? ''),
        ]);

        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'profile.save.error'));
        header('Location: /studenti/profilo');
        exit;
    }
}
