<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentQuestService;

class StudentQuestController
{
    public function index(): void
    {
        $pageData = (new StudentQuestService())->getQuestIndexPageData();
        $this->guard($pageData['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED);

        View::render('studenti/quest/index', array_merge($pageData, [
            'title' => 'student.quest.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/studenti/quest.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function map(string $questId): void
    {
        $pageData = (new StudentQuestService())->getQuestMapPageData((int) $questId);
        $this->guard($pageData['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED);

        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata o non disponibile per la classe selezionata.');
            header('Location: /studenti/quest');
            exit;
        }

        View::render('studenti/quest/map', array_merge($pageData, [
            'title' => 'student.quest.map.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/studenti/quest-map.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function problemDeliveries(string $questId): void
    {
        $pageData = (new StudentQuestService())->getProblemDeliveriesPageData((int) $questId);
        $this->guard($pageData['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED);

        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata o non disponibile per la classe selezionata.');
            header('Location: /studenti/quest');
            exit;
        }

        View::render('studenti/quest/problem-deliveries', array_merge($pageData, [
            'title' => 'student.quest.problems.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/studenti/quest-problem-deliveries.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function chapterDetail(string $questId, string $chapterId): void
    {
        $pageData = (new StudentQuestService())->getChapterDetailPageData((int) $questId, (int) $chapterId);
        $this->guard($pageData['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED);

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null) {
            Flash::add('danger', 'student.quest.chapter.not_found');
            header('Location: /studenti/quest/' . (int) $questId . '/piantina');
            exit;
        }

        View::render('studenti/quest/chapter-detail', array_merge($pageData, [
            'title' => 'student.quest.chapter.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/studenti/quest-chapter-detail.js',
            ],
            'useMathJax' => false,
        ]), 'mainStudLayout');
    }

    public function exerciseDetail(string $questId, string $chapterId, string $exerciseId): void
    {
        $pageData = (new StudentQuestService())->getExerciseDetailPageData((int) $questId, (int) $chapterId, (int) $exerciseId);
        $this->guard($pageData['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED);

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null || ($pageData['exercise'] ?? null) === null) {
            Flash::add('danger', 'student.quest.exercise.not_found');
            header('Location: /studenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId);
            exit;
        }

        if (!($pageData['accessAllowed'] ?? false)) {
            Flash::add('danger', 'Hai eseguito l\'accesso ad un esercizio al quale non puoi ancora accedere.');
            header('Location: /studenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId);
            exit;
        }

        View::render('studenti/quest/exercise-detail', array_merge($pageData, [
            'title' => 'student.quest.exercise.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/quest-legacy.css',
                '/css/esercizi.css',
                'https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.css',
            ],
            'pageScripts' => [
                '/assets/tinymce/tinymce.min.js',
                'https://unpkg.com/dropzone@5/dist/min/dropzone.min.js',
                '/js/studenti/quest-exercise-detail.js',
            ],
            'useMathJax' => true,
        ]), 'mainStudLayout');
    }

    public function submitExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $result = (new StudentQuestService())->submitExercise(
            (int) $questId,
            (int) $chapterId,
            (int) $exerciseId,
            $_POST,
            $_FILES
        );

        if (($result['success'] ?? false) !== true) {
            Flash::add('danger', (string) ($result['message'] ?? 'student.quest.delivery.save_error'));
        } else {
            Flash::add('success', (string) ($result['message'] ?? 'student.quest.delivery.saved'));
        }

        $redirectUrl = '/studenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId . '/esercizi/' . (int) $exerciseId;
        if (($result['success'] ?? false) === true && !empty($result['forziere_vinto'])) {
            $query = http_build_query(['forziere_vinto' => (string) $result['forziere_vinto']]);
            $redirectUrl .= '?' . $query;
        }

        if (strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => (bool) ($result['success'] ?? false),
                'redirectUrl' => $redirectUrl,
            ]);
            exit;
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    public function deleteDeliveredFile(string $questId, string $chapterId, string $exerciseId): void
    {
        $result = (new StudentQuestService())->deleteDeliveredFile(
            (int) $questId,
            (int) $chapterId,
            (int) $exerciseId,
            (string) ($_POST['file_name'] ?? '')
        );

        if (($result['success'] ?? false) !== true) {
            Flash::add('danger', (string) ($result['message'] ?? 'Impossibile eliminare il file selezionato.'));
        } else {
            Flash::add('success', (string) ($result['message'] ?? 'File eliminato correttamente.'));
        }

        $redirectUrl = '/studenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId . '/esercizi/' . (int) $exerciseId;
        header('Location: ' . $redirectUrl);
        exit;
    }

    private function guard(int $permissionStatus): void
    {
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

        if ($permissionStatus === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        if ($permissionStatus === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
            exit;
        }
    }
}
