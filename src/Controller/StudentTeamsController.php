<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentTeamsService;
use App\Service\TranslationService;

class StudentTeamsController
{
    public function index(): void
    {
        $pageData = (new StudentTeamsService())->getPageData();

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

        View::render('studenti/squadra/index', array_merge($pageData, [
            'title' => 'student.teams.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/squadre.css',
            ],
            'pageScripts' => [
                '/js/studenti/squadra.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function save(): void
    {
        $result = (new StudentTeamsService())->saveTeam($_POST, $_FILES['emblema_squadra'] ?? []);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? $this->fallbackMessage());
        header('Location: /studenti/squadra');
        exit;
    }

    public function delete(string $teamId): void
    {
        $result = (new StudentTeamsService())->deleteTeam((int) $teamId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? $this->fallbackMessage());
        header('Location: /studenti/squadra');
        exit;
    }

    public function leave(): void
    {
        $result = (new StudentTeamsService())->leaveTeam();
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? $this->fallbackMessage());
        header('Location: /studenti/squadra');
        exit;
    }

    public function handleInvite(): void
    {
        $inviteId = isset($_POST['id_invito']) ? (int) $_POST['id_invito'] : 0;
        $action = (string) ($_POST['azione'] ?? '');

        $result = (new StudentTeamsService())->handleInvite($inviteId, $action);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? $this->fallbackMessage());
        header('Location: /studenti/squadra');
        exit;
    }

    private function fallbackMessage(): string
    {
        return (new TranslationService())->translate('student.teams.operation_completed');
    }
}
