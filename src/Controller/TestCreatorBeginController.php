<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\TestCreatorBeginService;
use App\Service\TranslationService;

// Controller dedicato al Test Creator.
class TestCreatorBeginController
{
    // Mostra la pagina iniziale del Test Creator.
    public function beginTestCreator(): void
    {
        $translator = new TranslationService();
        $service = new TestCreatorBeginService();
        $pageData = $service->getPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', $translator->translate('testcreator.controller.session_expired'));
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', $translator->translate('testcreator.controller.teacher_or_admin_only'));
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/index', array_merge($pageData, [
            'title' => 'testcreator.start',
            'pageScripts' => [
                '/js/testcreator/begin.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function subjects(): void
    {
        $service = new TestCreatorBeginService();
        $pageData = $service->getSubjectsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/subjects', array_merge($pageData, [
            'title' => 'testcreator.materie',
            'pageScripts' => [
                '/js/testcreator/subjects.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function subjectFormData(string $subjectId): void
    {
        $result = (new TestCreatorBeginService())->getSubjectFormData((int) $subjectId);
        $this->json($result, ($result['success'] ?? false) ? 200 : 400);
    }

    public function saveSubject(): void
    {
        $subjectId = isset($_POST['id_materia']) ? (int) $_POST['id_materia'] : 0;
        $subjectName = isset($_POST['nome_materia']) ? (string) $_POST['nome_materia'] : '';

        $result = (new TestCreatorBeginService())->saveSubject($subjectId, $subjectName);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'testcreator.subject.save.success'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.save.error'));
        }

        header('Location: /testcreator/materie');
        exit;
    }

    public function exportSubjectJson(string $subjectId): void
    {
        $result = (new TestCreatorBeginService())->buildSubjectExportPayload((int) $subjectId);
        if (!($result['success'] ?? false)) {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.export.error'));
            header('Location: /testcreator/materie');
            exit;
        }

        $subjectName = (string) ($result['subjectName'] ?? 'materia');
        $fileName = 'materia_' . preg_replace('/[^a-z0-9_-]+/i', '_', $subjectName) . '_' . date('Ymd_His') . '.json';

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        echo json_encode($result['payload'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function importSubjectJson(): void
    {
        $result = (new TestCreatorBeginService())->importSubjectFromJson($_FILES);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Materia importata correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.import.error'));
        }

        header('Location: /testcreator/materie');
        exit;
    }

    public function assignSubject(string $subjectId): void
    {
        $result = (new TestCreatorBeginService())->assignSubject((int) $subjectId);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Materia assegnata correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.assign.error'));
        }

        header('Location: /testcreator/materie');
        exit;
    }

    public function deleteSubject(string $subjectId): void
    {
        $result = (new TestCreatorBeginService())->deleteSubject((int) $subjectId);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Materia eliminata correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.delete.error'));
        }

        header('Location: /testcreator/materie');
        exit;
    }

    public function unassignSubject(string $subjectId): void
    {
        $result = (new TestCreatorBeginService())->unassignSubject((int) $subjectId);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Materia disassegnata correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.subject.unassign.error'));
        }

        $redirectTarget = '/testcreator/begin';
        $referer = isset($_SERVER['HTTP_REFERER']) ? (string) $_SERVER['HTTP_REFERER'] : '';
        if (str_contains($referer, '/testcreator/materie')) {
            $redirectTarget = '/testcreator/materie';
        }

        header('Location: ' . $redirectTarget);
        exit;
    }

    public function topics(): void
    {
        $service = new TestCreatorBeginService();
        $selectedSubjectId = isset($_GET['materia']) ? (int) $_GET['materia'] : 0;
        $pageData = $service->getTopicsPageData($selectedSubjectId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/topics', array_merge($pageData, [
            'title' => 'testcreator.argomenti',
            'pageScripts' => [
                '/js/testcreator/topics.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function saveTopic(): void
    {
        $topicId = isset($_POST['id_argomento']) ? (int) $_POST['id_argomento'] : 0;
        $subjectId = isset($_POST['fk_materia']) ? (int) $_POST['fk_materia'] : 0;
        $topicName = isset($_POST['nome_argomento']) ? (string) $_POST['nome_argomento'] : '';

        $result = (new TestCreatorBeginService())->saveTopic($topicId, $subjectId, $topicName);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Argomento salvato correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.topic.save.error'));
        }

        $redirectSubject = max(0, $subjectId);
        header('Location: /testcreator/argomenti' . ($redirectSubject > 0 ? ('?materia=' . $redirectSubject) : ''));
        exit;
    }

    public function deleteTopic(string $topicId): void
    {
        $topicIdInt = (int) $topicId;
        $subjectId = isset($_POST['fk_materia']) ? (int) $_POST['fk_materia'] : 0;
        $result = (new TestCreatorBeginService())->deleteTopic($topicIdInt);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Argomento eliminato correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.topic.delete.error'));
        }

        header('Location: /testcreator/argomenti' . ($subjectId > 0 ? ('?materia=' . $subjectId) : ''));
        exit;
    }

    public function questionTopics(): void
    {
        $service = new TestCreatorBeginService();
        $selectedSubjectId = isset($_GET['materia']) ? (int) $_GET['materia'] : 0;
        $pageData = $service->getQuestionTopicsPageData($selectedSubjectId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/questions-topics', array_merge($pageData, [
            'title' => 'testcreator.domande',
            'pageScripts' => [
                '/js/testcreator/questions-topics.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function importQuestionsMenu(): void
    {
        $service = new TestCreatorBeginService();
        $selectedSubjectId = isset($_GET['materia']) ? (int) $_GET['materia'] : 0;
        $pageData = $service->getImportQuestionsMenuPageData($selectedSubjectId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/import-questions-menu', array_merge($pageData, [
            'title' => 'testcreator.questions.import_menu.title',
            'pageScripts' => [
                '/js/testcreator/import-questions-menu.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function exportQuestions(): void
    {
        $service = new TestCreatorBeginService();
        $selectedSubjectId = isset($_GET['materia']) ? (int) $_GET['materia'] : 0;
        $pageData = $service->getExportQuestionsPageData($selectedSubjectId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/export-questions', array_merge($pageData, [
            'title' => 'testcreator.questions.export.title',
            'pageScripts' => [
                '/js/testcreator/export-questions.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function exportQuestionsCsv(): void
    {
        $topicIds = isset($_POST['topic_ids']) && is_array($_POST['topic_ids']) ? array_map('intval', $_POST['topic_ids']) : [];
        $result = (new TestCreatorBeginService())->buildQuestionsExportCsvRows($topicIds);

        if (($result['success'] ?? false) !== true) {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.csv.export.error'));
            header('Location: /testcreator/esporta-domande');
            exit;
        }

        header('Content-Type: text/csv; charset=ISO-8859-1');
        header('Content-Disposition: attachment; filename=dati_domande.csv');
        $output = fopen('php://output', 'w');
        foreach ($result['rows'] as $row) {
            fputcsv($output, $row, ';');
        }
        fclose($output);
        exit;
    }

    public function importQuestionsFromOtherTeachers(string $topicId): void
    {
        $pageData = (new TestCreatorBeginService())->getImportQuestionsFromOtherTeachersPageData((int) $topicId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['topic'] ?? null) === null) {
            Flash::add('danger', 'Argomento non trovato o non accessibile.');
            header('Location: /testcreator/import-domande');
            exit;
        }

        View::render('testcreator/import-questions-external', array_merge($pageData, [
            'title' => 'testcreator.questions.import_external.title',
            'pageScripts' => [
                '/js/testcreator/import-questions-external.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function importQuestionFromOtherTeacher(string $questionId): void
    {
        $result = (new TestCreatorBeginService())->importQuestionFromOtherTeacher((int) $questionId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . (string) ($result['redirect'] ?? '/testcreator/import-domande'));
        exit;
    }

    public function importQuestionAnswersPreview(string $questionId): void
    {
        $result = (new TestCreatorBeginService())->getQuestionAnswersPreview((int) $questionId);
        $this->json($result, ($result['success'] ?? false) ? 200 : 400);
    }

    public function importQuestionsFromCsvForm(string $topicId = '0'): void
    {
        $pageData = (new TestCreatorBeginService())->getImportQuestionsFromCsvPageData((int) $topicId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if ((int) $topicId > 0 && ($pageData['topic'] ?? null) === null) {
            Flash::add('danger', 'Argomento non trovato o non accessibile.');
            header('Location: /testcreator/import-domande');
            exit;
        }

        View::render('testcreator/import-questions-csv', array_merge($pageData, [
            'title' => 'testcreator.questions.import_csv.title',
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function importQuestionsFromCsvSubmit(string $topicId = '0'): void
    {
        $result = (new TestCreatorBeginService())->importQuestionsFromCsv((int) $topicId, $_FILES['fileUpload'] ?? []);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . (string) ($result['redirect'] ?? '/testcreator/import-domande'));
        exit;
    }

    public function questionsByTopic(string $topicId): void
    {
        $pageData = (new TestCreatorBeginService())->getQuestionsByTopicPageData((int) $topicId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['topic'] ?? null) === null) {
            Flash::add('danger', 'Argomento non trovato o non accessibile.');
            header('Location: /testcreator/domande');
            exit;
        }

        View::render('testcreator/questions-list', array_merge($pageData, [
            'title' => 'testcreator.domande',
            'pageScripts' => [
                '/js/testcreator/questions-list.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function newQuestion(string $topicId): void
    {
        $pageData = (new TestCreatorBeginService())->getQuestionFormPageData((int) $topicId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['topic'] ?? null) === null) {
            Flash::add('danger', 'Argomento non trovato o non accessibile.');
            header('Location: /testcreator/domande');
            exit;
        }

        View::render('testcreator/question-form', array_merge($pageData, [
            'title' => 'testcreator.nuova_domanda',
            'pageScripts' => [
                '/assets/tinymce/tinymce.min.js',
                '/js/testcreator/question-form.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function editQuestion(string $questionId): void
    {
        $pageData = (new TestCreatorBeginService())->getQuestionEditPageData((int) $questionId);

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['question'] ?? null) === null || ($pageData['topic'] ?? null) === null) {
            Flash::add('danger', 'Domanda non trovata o non accessibile.');
            header('Location: /testcreator/domande');
            exit;
        }

        View::render('testcreator/question-form', array_merge($pageData, [
            'title' => 'testcreator.modifica_domanda',
            'pageScripts' => [
                '/assets/tinymce/tinymce.min.js',
                '/js/testcreator/question-form.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function saveQuestion(): void
    {
        $result = (new TestCreatorBeginService())->saveQuestion($_POST);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/domande');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function updateQuestion(string $questionId): void
    {
        $payload = $_POST;
        $payload['id_domanda'] = (int) $questionId;
        $result = (new TestCreatorBeginService())->updateQuestion($payload);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/domande');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function removeQuestion(string $questionId): void
    {
        $topicId = isset($_POST['topic_id']) ? (int) $_POST['topic_id'] : 0;
        $result = (new TestCreatorBeginService())->removeQuestionAssignment((int) $questionId);
        $redirect = $topicId > 0 ? '/testcreator/domande/argomenti/' . $topicId : '/testcreator/domande';
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function removeQuestionPermanently(string $questionId): void
    {
        $topicId = isset($_POST['topic_id']) ? (int) $_POST['topic_id'] : 0;
        $result = (new TestCreatorBeginService())->deleteQuestionPermanently((int) $questionId);
        $redirect = $topicId > 0 ? '/testcreator/domande/argomenti/' . $topicId : '/testcreator/domande';
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function uploadQuestionEditorImage(): void
    {
        $payload = (new TestCreatorBeginService())->uploadQuestionEditorImage($_FILES);
        $this->json($payload, isset($payload['location']) ? 200 : 400);
    }

    public function books(): void
    {
        $pageData = (new TestCreatorBeginService())->getBooksManagementPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/books', array_merge($pageData, [
            'title' => 'testcreator.books.title',
            'pageScripts' => [
                '/js/testcreator/books.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function bookFormData(string $bookId): void
    {
        $result = (new TestCreatorBeginService())->getBookFormData((int) $bookId);
        $this->json($result, ($result['success'] ?? false) ? 200 : 400);
    }

    public function saveBook(): void
    {
        $bookId = isset($_POST['id_libro_testo']) ? (int) $_POST['id_libro_testo'] : 0;
        $title = isset($_POST['titolo_libro']) ? (string) $_POST['titolo_libro'] : '';
        $publisher = isset($_POST['casa_editrice']) ? (string) $_POST['casa_editrice'] : '';
        $authors = isset($_POST['autori']) ? (string) $_POST['autori'] : '';

        $result = (new TestCreatorBeginService())->saveBook($bookId, $title, $publisher, $authors);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Libro salvato correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.book.save.error'));
        }

        header('Location: /testcreator/libri');
        exit;
    }

    public function deactivateBook(string $bookId): void
    {
        $result = (new TestCreatorBeginService())->deactivateBook((int) $bookId);

        if (($result['success'] ?? false) === true) {
            Flash::add('success', (string) ($result['message'] ?? 'Libro disattivato correttamente.'));
        } else {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.book.disable.error'));
        }

        header('Location: /testcreator/libri');
        exit;
    }

    public function grids(): void
    {
        $pageData = (new TestCreatorBeginService())->getGridsPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/grids', array_merge($pageData, [
            'title' => 'testcreator.grids.title',
            'pageScripts' => [
                '/js/testcreator/grids.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function newGrid(): void
    {
        $pageData = (new TestCreatorBeginService())->getGridFormPageData(0);
        $this->renderGridFormOrRedirect($pageData);
    }

    public function editGrid(string $gridId): void
    {
        $pageData = (new TestCreatorBeginService())->getGridFormPageData((int) $gridId);
        $this->renderGridFormOrRedirect($pageData);
    }

    public function saveGrid(): void
    {
        $result = (new TestCreatorBeginService())->saveGrid($_POST);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/griglie');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function updateGrid(string $gridId): void
    {
        $payload = $_POST;
        $payload['id_griglia'] = (int) $gridId;
        $result = (new TestCreatorBeginService())->updateGrid($payload);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/griglie');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function deleteGrid(string $gridId): void
    {
        $result = (new TestCreatorBeginService())->deleteGrid((int) $gridId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/griglie');
        exit;
    }

    public function quizzes(): void
    {
        $service = new TestCreatorBeginService();
        $pageData = $service->getQuizzesPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/quizzes', array_merge($pageData, [
            'title' => 'testcreator.quiz',
            'pageScripts' => [
                '/js/testcreator/quizzes.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function newQuiz(): void
    {
        $pageData = (new TestCreatorBeginService())->getQuizFormPageData(0);
        $this->renderQuizFormOrRedirect($pageData);
    }

    public function editQuiz(string $quizId): void
    {
        $pageData = (new TestCreatorBeginService())->getQuizFormPageData((int) $quizId);
        $this->renderQuizFormOrRedirect($pageData);
    }

    public function saveQuiz(): void
    {
        $result = (new TestCreatorBeginService())->saveQuiz($_POST);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/quiz');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function updateQuiz(string $quizId): void
    {
        $payload = $_POST;
        $payload['id_quiz'] = (int) $quizId;
        $result = (new TestCreatorBeginService())->updateQuiz($payload);
        $redirect = (string) ($result['redirect'] ?? '/testcreator/quiz');
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . $redirect);
        exit;
    }

    public function deleteQuiz(string $quizId): void
    {
        $result = (new TestCreatorBeginService())->deleteQuiz((int) $quizId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/quiz');
        exit;
    }

    public function generateQuiz(string $quizId): void
    {
        $pageData = (new TestCreatorBeginService())->getQuizGenerationPageData((int) $quizId);
        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'Accesso non autorizzato.');
            header('Location: /loginDoc');
            exit;
        }
        if (($pageData['quizNotFound'] ?? false) === true) {
            Flash::add('danger', 'Quiz non trovato o non accessibile.');
            header('Location: /testcreator/quiz');
            exit;
        }
        if (($pageData['errorMessage'] ?? '') !== '') {
            Flash::add('danger', (string) $pageData['errorMessage']);
            header('Location: /testcreator/quiz');
            exit;
        }

        View::render('testcreator/quiz-generate', array_merge($pageData, [
            'title' => 'testcreator.quiz',
            'pageScripts' => [],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function printDsaOptions(string $quizId): void
    {
        $this->streamQuizPdf((int) $quizId, [
            'dsa' => true,
            'perc20meno' => isset($_GET['perc20meno']),
            'risp3' => isset($_GET['risp3']),
            'testobig' => isset($_GET['testobig']),
            'opendyslexic' => isset($_GET['opendyslexic']),
        ]);
    }

    public function exportQuizCsv(string $quizId): void
    {
        $result = (new TestCreatorBeginService())->buildQuizCsvRows((int) $quizId);
        if (($result['success'] ?? false) !== true) {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.csv.export.error'));
            header('Location: /testcreator/quiz');
            exit;
        }

        header('Content-Type: text/csv; charset=ISO-8859-1');
        header('Content-Disposition: attachment; filename=quiz.csv');
        $output = fopen('php://output', 'w');
        foreach ($result['rows'] as $row) {
            fputcsv($output, $row, ';');
        }
        fclose($output);
        exit;
    }

    public function printCoCorrection(string $quizId): void
    {
        $this->streamQuizPdf((int) $quizId, [
            'cocorrezione' => true,
            'qta_cocorrezione' => max(1, (int) ($_GET['qta_cocorrezione'] ?? 1)),
        ]);
    }

    public function printQuiz(string $quizId): void
    {
        $this->streamQuizPdf((int) $quizId, []);
    }

    public function manualQuestionSelection(string $quizId): void
    {
        $pageData = (new TestCreatorBeginService())->getManualQuestionSelectionPageData((int) $quizId);
        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'Accesso non autorizzato.');
            header('Location: /loginDoc');
            exit;
        }
        if (($pageData['quizNotFound'] ?? false) === true) {
            Flash::add('danger', 'Quiz non trovato o non accessibile.');
            header('Location: /testcreator/quiz');
            exit;
        }

        View::render('testcreator/quiz-question-selection', array_merge($pageData, [
            'title' => 'testcreator.quiz',
            'pageScripts' => [],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function saveManualQuestionSelection(string $quizId): void
    {
        $selected = is_array($_POST['domande'] ?? null) ? $_POST['domande'] : [];
        $result = (new TestCreatorBeginService())->saveManualQuestionSelection((int) $quizId, $selected);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: ' . (string) ($result['redirect'] ?? '/testcreator/quiz'));
        exit;
    }


    public function printTemplates(): void
    {
        $pageData = (new TestCreatorBeginService())->getPrintTemplatesPageData();

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        View::render('testcreator/print-templates', array_merge($pageData, [
            'title' => 'testcreator.print_templates.title',
            'pageScripts' => [],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function selectPrintTemplate(): void
    {
        $templateName = isset($_POST['template_name']) ? (string) $_POST['template_name'] : '';
        $result = (new TestCreatorBeginService())->selectPrintTemplate($templateName);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/template-stampa');
        exit;
    }

    public function uploadPrintTemplate(): void
    {
        $result = (new TestCreatorBeginService())->uploadPrintTemplate($_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/template-stampa');
        exit;
    }

    public function teacherEmails(): void
    {
        $pageData = (new TestCreatorBeginService())->getTeacherEmailsPageData();
        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }
        if (($pageData['isAdmin'] ?? false) !== true) {
            Flash::add('danger', 'Operazione consentita solo agli amministratori.');
            header('Location: /testcreator/begin');
            exit;
        }

        View::render('testcreator/teacher-emails', array_merge($pageData, [
            'title' => 'testcreator.teacher_emails.title',
            'pageScripts' => [
                '/js/testcreator/teacher-emails.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function saveTeacherEmail(): void
    {
        $email = isset($_POST['mail']) ? (string) $_POST['mail'] : '';
        $result = (new TestCreatorBeginService())->saveTeacherEmail($email);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/mail-docenti');
        exit;
    }

    public function importTeacherEmailsCsv(): void
    {
        $result = (new TestCreatorBeginService())->importTeacherEmailsCsv($_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/mail-docenti');
        exit;
    }

    public function deleteTeacherEmail(string $emailId): void
    {
        $result = (new TestCreatorBeginService())->deleteTeacherEmail((int) $emailId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/mail-docenti');
        exit;
    }

    public function administrators(): void
    {
        $pageData = (new TestCreatorBeginService())->getAdministratorsPageData();
        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }
        if (($pageData['isAdmin'] ?? false) !== true) {
            Flash::add('danger', 'Operazione consentita solo agli amministratori.');
            header('Location: /testcreator/begin');
            exit;
        }

        View::render('testcreator/administrators', array_merge($pageData, [
            'title' => 'testcreator.administrators.title',
            'pageScripts' => [
                '/js/testcreator/administrators.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    public function promoteAdministrator(string $userId): void
    {
        $result = (new TestCreatorBeginService())->promoteAdministrator((int) $userId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/amministratori');
        exit;
    }

    public function removeAdministrator(string $userId): void
    {
        $result = (new TestCreatorBeginService())->removeAdministrator((int) $userId);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione completata.'));
        header('Location: /testcreator/amministratori');
        exit;
    }

    private function renderQuizFormOrRedirect(array $pageData): void
    {
        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['quizNotFound'] ?? false) === true) {
            Flash::add('danger', 'Quiz non trovato o non accessibile.');
            header('Location: /testcreator/quiz');
            exit;
        }

        View::render('testcreator/quiz-form', array_merge($pageData, [
            'title' => ((int) ($pageData['quiz']['id_quiz'] ?? 0) > 0) ? 'testcreator.modifica_quiz' : 'testcreator.nuovo_quiz',
            'pageScripts' => [
                '/js/testcreator/quiz-form.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    private function renderGridFormOrRedirect(array $pageData): void
    {
        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            Flash::add('danger', 'Sessione scaduta. Effettua nuovamente il login.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_TEACHER) {
            Flash::add('danger', 'Accesso consentito solo a docenti o amministratori.');
            header('Location: /loginDoc');
            exit;
        }

        if (($pageData['gridNotFound'] ?? false) === true) {
            Flash::add('danger', 'Griglia non trovata o non accessibile.');
            header('Location: /testcreator/griglie');
            exit;
        }

        View::render('testcreator/grid-form', array_merge($pageData, [
            'title' => ((int) ($pageData['grid']['id_griglia'] ?? 0) > 0) ? 'testcreator.grid.edit' : 'testcreator.grid.new',
            'pageScripts' => [
                '/assets/tinymce/tinymce.min.js',
                '/js/testcreator/grid-form.js',
            ],
            'useMathJax' => false,
        ]), 'testCreatorLayout');
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function streamQuizPdf(int $quizId, array $options): void
    {
        $result = (new TestCreatorBeginService())->buildQuizPdf($quizId, $options);
        if (($result['success'] ?? false) !== true) {
            Flash::add('danger', (string) ($result['message'] ?? 'testcreator.pdf.generate.error'));
            header('Location: /testcreator/quiz');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="quiz.pdf"');
        echo (string) ($result['pdf'] ?? '');
        exit;
    }
}
