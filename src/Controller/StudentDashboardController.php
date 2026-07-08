<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentDashboardService;

// Controller dell'area studenti: selezione classe iniziale e dashboard interna della classe scelta.
class StudentDashboardController
{
    // Mostra la pagina iniziale con l'elenco delle classi disponibili per lo studente autenticato.
    public function index(): void
    {
        $service = new StudentDashboardService();
        $data = $service->getSelectionPageData();

        $permissionStatus = $data['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED;
        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        View::render('studenti/dashboard', array_merge($data, [
            'title' => 'student.dashboard.title',
            // Nella pagina di selezione classe non serve caricare notifiche/topbar legate alla classe corrente.
            'disableStudentTopbarData' => true,
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    // Salva in sessione la classe selezionata e reindirizza alla prima pagina interna della classe.
    public function selectClass(string $classId): void
    {
        $selected = (new StudentDashboardService())->selectClass((int) $classId);

        if (!$selected) {
            Flash::add('danger', 'student.classes.select.error');
            header('Location: /studenti/dashboard');
            exit;
        }

        header('Location: /studenti/classe/dashboard');
        exit;
    }

    // Mostra la dashboard interna della classe, riallineata alla pagina legacy del personaggio studente.
    public function showClassDashboard(): void
    {
        $service = new StudentDashboardService();
        $data = $service->getClassDashboardData();
        $permissionStatus = $data['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED;

        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            Flash::add('danger', 'permission.noclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'permission.notyourclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        View::render('studenti/classDashboard', array_merge($data, [
            'title' => 'student.class.dashboard.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/student-class-dashboard.css',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    // Mostra la pagina "I miei compagni" con tab compagni di classe e squadre avversarie.
    public function showClassmates(): void
    {
        $service = new StudentDashboardService();
        $data = $service->getClassmatesPageData();
        $permissionStatus = $data['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED;

        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NO_CLASS) {
            Flash::add('danger', 'permission.noclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'permission.notyourclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        View::render('studenti/classmates', array_merge($data, [
            'title' => 'student.classmates.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/classmates.css',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    // Registra la scelta del personaggio quando lo studente non l'ha ancora selezionato.
    public function chooseCharacter(): void
    {
        $characterId = (int) ($_POST['character_id'] ?? 0);
        $chosen = $characterId > 0 && (new StudentDashboardService())->chooseCharacter($characterId);

        Flash::add($chosen ? 'success' : 'danger', $chosen
            ? 'Personaggio selezionato correttamente.'
            : 'student.dashboard.character.select.error');

        header('Location: /studenti/classe/dashboard');
        exit;
    }

    public function activateTeamPower(): void
    {
        $result = (new StudentDashboardService())->activateTeamPower();
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'Operazione completata.');
        header('Location: /studenti/classe/dashboard');
        exit;
    }
}
