<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherTeamsService;

class TeacherTeamsController
{
    public function index(): void
    {
        $pageData = (new TeacherTeamsService())->getTeamsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        View::render('docenti/squadre/index', array_merge($pageData, [
            'title' => 'teacher.teams.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/squadre.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherTeamsService())->saveTeam($_POST, $_FILES['team_emblem'] ?? []));
    }

    public function delete(string $teamId): void
    {
        $this->json((new TeacherTeamsService())->deleteTeam((int) $teamId));
    }

    public function approve(string $teamId): void
    {
        $this->json((new TeacherTeamsService())->approveStudentTeam((int) $teamId));
    }

    public function reject(string $teamId): void
    {
        $this->json((new TeacherTeamsService())->rejectStudentTeam((int) $teamId));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
