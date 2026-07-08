<?php

namespace App\Controller;

use App\Core\View;
use App\Service\PermissionService;
use App\Service\StudentManagementService;
use App\Service\Flash;
use App\Service\Session;

class StudentManagementController
{
    public function index(): void
    {
        $pageData = (new StudentManagementService())->getStudentListPageData();

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

        View::render('docenti/studenti/index', array_merge($pageData, [
            'title' => 'teacher.students.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/studenti.js',
            ],
            'pageModals' => [
                [
                    'view' => 'docenti/modals/studentManagementModals',
                    'data' => [
                        'classroom' => $pageData['classroom'] ?? null,
                        'availableImportClasses' => $pageData['availableImportClasses'] ?? [],
                    ],
                ],
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function store(): void
    {
        $this->json((new StudentManagementService())->saveStudent($_POST));
    }

    public function importCsv(): void
    {
        $this->json((new StudentManagementService())->importStudentsFromCsv($_FILES['fileUpload'] ?? []));
    }

    public function importFromClass(): void
    {
        $sourceClassId = isset($_POST['id_classe_scelta']) ? (int) $_POST['id_classe_scelta'] : 0;
        $this->json((new StudentManagementService())->importStudentsFromClass($sourceClassId));
    }

    public function delete(string $studentId): void
    {
        $this->json((new StudentManagementService())->deleteStudent((int) $studentId));
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
