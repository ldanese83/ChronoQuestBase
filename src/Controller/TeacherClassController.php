<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\TeacherClassService;

// Controller della pagina di selezione/creazione classi per i docenti.
class TeacherClassController
{
    // Mostra la dashboard-card con le classi del docente.
    public function index(): void
    {
        $data = (new TeacherClassService())->getSelectionPageData();

        if (($data['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED) === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginDoc');
            exit;
        }

        View::render('docenti/classi', array_merge($data, [
            'title' => 'teacher.classes.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
            ],
            'pageScripts' => [
                '/js/docenti/classes.js',
            ],
            'pageModals' => [[
                'view' => 'docenti/modals/teacherClassCreateModal',
                'data' => [
                    'schoolYears' => $data['schoolYears'] ?? [],
                    'availableIcons' => $data['availableIcons'] ?? [],
                    'classes' => $data['classes'] ?? [],
                ],
            ]],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    // Salva una nuova classe e la seleziona immediatamente in sessione.
    public function store(): void
    {
        $service = new TeacherClassService();
        $created = $service->createClass(
            trim($_POST['class_name'] ?? ''),
            (int) ($_POST['school_year_id'] ?? 0),
            trim($_POST['class_icon'] ?? ''),
            trim($_POST['class_color'] ?? '')
        );

        Flash::add(
            $created ? 'success' : 'danger',
            $created ? 'teacher.classes.create.success' : 'teacher.classes.create.error'
        );

        header('Location: ' . ($created ? '/docenti/dashboard' : '/docenti/classi'));
        exit;
    }

    // Seleziona una classe esistente e porta il docente alla dashboard.
    public function select(string $classId): void
    {
        $selected = (new TeacherClassService())->selectClass((int) $classId);

        /*Flash::add(
            $selected ? 'success' : 'danger',
            $selected ? 'teacher.classes.select.success' : 'teacher.classes.select.error'
        );*/

        header('Location: ' . ($selected ? '/docenti/dashboard' : '/docenti/classi'));
        exit;
    }

    // Aggiorna i dati base (nome/colore/icona) di una classe.
    public function update(string $classId): void
    {
        $service = new TeacherClassService();
        $updated = $service->updateClass(
            (int) $classId,
            trim($_POST['class_name'] ?? ''),
            trim($_POST['class_icon'] ?? ''),
            trim($_POST['class_color'] ?? '')
        );

        Flash::add(
            $updated ? 'success' : 'danger',
            $updated ? 'teacher.classes.update.success' : 'teacher.classes.update.error'
        );

        header('Location: /docenti/classi');
        exit;
    }
}
