<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherQuestService;

class TeacherQuestController
{
    public function index(): void
    {
        $pageData = (new TeacherQuestService())->getQuestPageData();

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

        View::render('docenti/quest/index', array_merge($pageData, [
            'title' => 'teacher.quest.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherQuestService())->saveQuest($_POST, $_FILES));
    }

    public function delete(string $questId): void
    {
        $this->json((new TeacherQuestService())->deleteQuest((int) $questId));
    }

    public function map(string $questId): void
    {
        $pageData = (new TeacherQuestService())->getQuestMapPageData((int) $questId);

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

        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata nella classe selezionata.');
            header('Location: /docenti/quest');
            exit;
        }

        View::render('docenti/quest/map', array_merge($pageData, [
            'title' => 'teacher.quest.map.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-map.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function unevaluatedDeliveries(string $questId): void
    {
        $pageData = (new TeacherQuestService())->getUnevaluatedDeliveriesPageData((int) $questId);

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

        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata nella classe selezionata.');
            header('Location: /docenti/quest');
            exit;
        }

        View::render('docenti/quest/unevaluated-deliveries', array_merge($pageData, [
            'title' => 'teacher.quest.unevaluated.title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-unevaluated-deliveries.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function saveDeliveryProblem(string $questId, string $deliveryId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_consegna'] = (int) $deliveryId;

        $result = (new TeacherQuestService())->saveDeliveryProblem($payload);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? ''));
        header('Location: ' . (string) ($result['redirectUrl'] ?? ('/docenti/quest/' . (int) $questId . '/consegne-non-valutate')));
        exit;
    }

    public function createChapter(string $questId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;

        $this->json((new TeacherQuestService())->createChapter($payload));
    }

    public function chapterList(string $questId): void
    {
        $pageData = (new TeacherQuestService())->getQuestMapPageData((int) $questId);

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

        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata nella classe selezionata.');
            header('Location: /docenti/quest');
            exit;
        }

        View::render('docenti/quest/chapter-list', array_merge($pageData, [
            'title' => 'teacher.quest.chapter_list.page_title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-chapter-list.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function updateChapter(string $questId, string $chapterId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_capitolo'] = (int) $chapterId;

        $this->json((new TeacherQuestService())->updateChapter($payload));
    }

    public function chapterDetail(string $questId, string $chapterId): void
    {
        $pageData = (new TeacherQuestService())->getChapterDetailPageData((int) $questId, (int) $chapterId);

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

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null) {
            Flash::add('danger', 'teacher.quest.chapter.not_found');
            header('Location: /docenti/quest/' . (int) $questId . '/piantina');
            exit;
        }

        View::render('docenti/quest/chapter-detail', array_merge($pageData, [
            'title' => 'teacher.quest.edit',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-chapter-detail.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function addExercise(string $questId, string $chapterId): void
    {
        $pageData = (new TeacherQuestService())->getAddExercisePageData((int) $questId, (int) $chapterId);

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

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null) {
            Flash::add('danger', 'teacher.quest.chapter.not_found');
            header('Location: /docenti/quest/' . (int) $questId . '/piantina');
            exit;
        }

        View::render('docenti/quest/add-exercise', array_merge($pageData, [
            'title' => 'teacher.quest.exercise.add',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-add-exercise.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function saveExercise(string $questId, string $chapterId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_capitolo'] = (int) $chapterId;

        $this->json((new TeacherQuestService())->saveExercise($payload));
    }

    public function editExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $this->renderExerciseEditorPage((int) $questId, (int) $chapterId, (int) $exerciseId, false);
    }

    public function viewExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $this->renderExerciseEditorPage((int) $questId, (int) $chapterId, (int) $exerciseId, true);
    }

    public function updateExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_capitolo'] = (int) $chapterId;
        $payload['id_esercizio'] = (int) $exerciseId;

        $this->json((new TeacherQuestService())->updateExercise($payload));
    }

    public function exerciseDeliveries(string $questId, string $chapterId, string $exerciseId): void
    {
        $pageData = (new TeacherQuestService())->getExerciseDeliveriesPageData((int) $questId, (int) $chapterId, (int) $exerciseId);
        $this->renderDeliveryPageOrRedirect($pageData, (int) $questId, (int) $chapterId, 'teacher.quest.delivery.exercise.title', 'docenti/quest/exercise-deliveries', [
            '/js/docenti/quest-exercise-deliveries.js',
        ]);
    }

    public function studentDelivery(string $questId, string $chapterId, string $exerciseId, string $studentId): void
    {
        $pageData = (new TeacherQuestService())->getStudentDeliveryPageData((int) $questId, (int) $chapterId, (int) $exerciseId, (int) $studentId);
        $this->renderDeliveryPageOrRedirect($pageData, (int) $questId, (int) $chapterId, 'teacher.quest.delivery.student.title', 'docenti/quest/student-delivery', [
            '/js/docenti/quest-student-delivery.js',
        ]);
    }

    public function saveDeliveryEvaluation(string $questId, string $chapterId, string $exerciseId, string $studentId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_capitolo'] = (int) $chapterId;
        $payload['id_esercizio'] = (int) $exerciseId;
        $payload['id_studente'] = (int) $studentId;

        $result = (new TeacherQuestService())->saveDeliveryEvaluation($payload);
        if (($result['success'] ?? false) && !empty($result['redirectUrl'])) {
            Flash::add('success', (string) ($result['message'] ?? 'teacher.quest.delivery.evaluation.saved'));
            header('Location: ' . $result['redirectUrl']);
            exit;
        }

        Flash::add('danger', (string) ($result['message'] ?? 'teacher.quest.save.error'));
        header('Location: /docenti/quest/' . (int) $questId . '/capitolo/' . (int) $chapterId . '/esercizi/' . (int) $exerciseId . '/consegne/' . (int) $studentId);
        exit;
    }

    public function suggestGeminiEvaluation(string $questId, string $chapterId, string $exerciseId, string $studentId): void
    {
        $payload = $_POST;
        $payload['id_quest'] = (int) $questId;
        $payload['id_capitolo'] = (int) $chapterId;
        $payload['id_esercizio'] = (int) $exerciseId;
        $payload['id_studente'] = (int) $studentId;
        $this->json((new TeacherQuestService())->suggestGeminiEvaluation($payload));
    }

    public function activateExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $result = (new TeacherQuestService())->activateExercise((int) $questId, (int) $chapterId, (int) $exerciseId);
        Flash::add($result['success'] ? 'success' : 'danger', $result['message'] ?? '');
        header('Location: /docenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId);
        exit;
    }

    public function deleteExercise(string $questId, string $chapterId, string $exerciseId): void
    {
        $result = (new TeacherQuestService())->deleteExercise((int) $questId, (int) $chapterId, (int) $exerciseId);
        Flash::add($result['success'] ? 'success' : 'danger', $result['message'] ?? '');
        header('Location: /docenti/quest/' . (int) $questId . '/capitoli/' . (int) $chapterId);
        exit;
    }

    public function exerciseTopics(string $subjectId): void
    {
        $this->json((new TeacherQuestService())->getTopicsForSubject((int) $subjectId));
    }

    public function exerciseMaterials(string $topicId): void
    {
        $this->json((new TeacherQuestService())->getMaterialsForTopic((int) $topicId));
    }

    public function uploadEditorImage(): void
    {
        $payload = (new TeacherQuestService())->uploadEditorImage($_FILES, $_REQUEST);
        $statusCode = isset($payload['location']) ? 201 : 400;
        $this->json($payload, $statusCode);
    }

    public function importExportMenu(): void
    {
        $pageData = (new TeacherQuestService())->getImportExportMenuPageData();
        $this->renderQuestImportExportPageOrRedirect($pageData, 'teacher.quest.import_export.title', 'docenti/quest/import-export-menu');
    }

    public function externalOriginalQuests(): void
    {
        $pageData = (new TeacherQuestService())->getExternalOriginalQuestsPageData();
        $this->renderQuestImportExportPageOrRedirect($pageData, 'teacher.quest.import_external.title', 'docenti/quest/import-external-list');
    }

    public function externalOriginalQuestExercises(string $questId): void
    {
        $pageData = (new TeacherQuestService())->getExternalOriginalQuestExercisesPageData((int) $questId);
        if (($pageData['quest'] ?? null) === null) {
            Flash::add('danger', 'Quest non trovata.');
            header('Location: /docenti/quest/import-export/altre-classi');
            exit;
        }
        $this->renderQuestImportExportPageOrRedirect($pageData, 'teacher.quest.import_external.exercises.title', 'docenti/quest/import-external-exercises');
    }

    public function importOriginalQuestFromAnotherClass(string $questId): void
    {
        $result = (new TeacherQuestService())->importOriginalQuestFromAnotherClass((int) $questId);
        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione non riuscita.'));
        header('Location: /docenti/quest/import-export/altre-classi');
        exit;
    }

    public function exportQuestArchive(string $questId): void
    {
        $result = (new TeacherQuestService())->buildQuestExportArchive((int) $questId);
        if (!($result['success'] ?? false)) {
            Flash::add('danger', (string) ($result['message'] ?? 'Export non riuscito.'));
            header('Location: /docenti/quest/import-export');
            exit;
        }

        $absolutePath = (string) ($result['absolutePath'] ?? '');
        $fileName = (string) ($result['fileName'] ?? 'quest-export.zip');
        if ($absolutePath === '' || !is_file($absolutePath)) {
            Flash::add('danger', 'File export non disponibile.');
            header('Location: /docenti/quest/import-export');
            exit;
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
        @unlink($absolutePath);
        exit;
    }

    public function importQuestArchive(): void
    {
        $topicResolutionRaw = isset($_POST['topic_resolution']) ? (string) $_POST['topic_resolution'] : '';
        $topicResolution = [];
        if ($topicResolutionRaw !== '') {
            $decoded = json_decode($topicResolutionRaw, true);
            if (is_array($decoded)) {
                $topicResolution = $decoded;
            }
        }

        $result = (new TeacherQuestService())->importQuestFromArchive($_FILES, $topicResolution);
        $isAjax = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
        if ($isAjax) {
            $status = ($result['success'] ?? false) || ($result['requires_topic_resolution'] ?? false) ? 200 : 400;
            $this->json($result, $status);
            return;
        }

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Import non riuscito.'));
        header('Location: /docenti/quest/import-export');
        exit;
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    private function renderExerciseEditorPage(int $questId, int $chapterId, int $exerciseId, bool $readOnly): void
    {
        $pageData = (new TeacherQuestService())->getExerciseEditorPageData($questId, $chapterId, $exerciseId);

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

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null || ($pageData['exercise'] ?? null) === null) {
            Flash::add('danger', 'teacher.quest.exercise.not_found');
            header('Location: /docenti/quest/' . $questId . '/capitoli/' . $chapterId);
            exit;
        }

        View::render('docenti/quest/add-exercise', array_merge($pageData, [
            'title' => $readOnly ? 'teacher.quest.exercise.view' : 'teacher.quest.exercise.edit',
            'exerciseMode' => $readOnly ? 'view' : 'edit',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/quest-add-exercise.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    private function renderDeliveryPageOrRedirect(array $pageData, int $questId, int $chapterId, string $title, string $view, array $pageScripts): void
    {
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

        if (($pageData['quest'] ?? null) === null || ($pageData['chapter'] ?? null) === null || ($pageData['exercise'] ?? null) === null) {
            Flash::add('danger', 'teacher.quest.exercise.data_not_found');
            header('Location: /docenti/quest/' . $questId . '/capitoli/' . $chapterId);
            exit;
        }

        View::render($view, array_merge($pageData, [
            'title' => $title,
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => $pageScripts,
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    private function renderQuestImportExportPageOrRedirect(array $pageData, string $title, string $view): void
    {
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

        View::render($view, array_merge($pageData, [
            'title' => $title,
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }
}
