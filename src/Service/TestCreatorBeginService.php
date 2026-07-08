<?php

namespace App\Service;

use PDO;
use Throwable;
use ZipArchive;

class TestCreatorBeginService
{
    private const QUESTIONS_CSV_MAX_SIZE = 500000;

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    private function tr(string $key): string
    {
        return $this->translator->translate($key);
    }

    public function getPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'subjects' => [],
            'userDisplayName' => '',
            'isAdmin' => false,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['subjects'] = $this->getAssignedSubjects($userId);
        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['isAdmin'] = $this->isAdmin($userId);

        return $data;
    }

    public function getSubjectsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'subjects' => [],
            'userDisplayName' => '',
            'isAdmin' => false,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $isAdmin = $this->isAdmin($userId);

        $data['subjects'] = $this->getAllSubjectsWithAssignmentState($userId);
        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['isAdmin'] = $isAdmin;

        return $data;
    }

    public function getSubjectFormData(int $subjectId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        if ($subjectId <= 0) {
            return [
                'success' => true,
                'subject' => [
                    'id_materia' => 0,
                    'nome_materia' => '',
                ],
            ];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_materia, uuid, nome_materia
             FROM ct_materie
             WHERE id_materia = :id_materia
             LIMIT 1'
        );
        $stmt->execute(['id_materia' => $subjectId]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subject === false) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.not_found')];
        }

        return [
            'success' => true,
            'subject' => [
                'id_materia' => (int) ($subject['id_materia'] ?? 0),
                'nome_materia' => (string) ($subject['nome_materia'] ?? ''),
            ],
        ];
    }

    public function saveSubject(int $subjectId, string $subjectName): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        $subjectName = trim($subjectName);
        if ($subjectName === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.name_required')];
        }

        if (mb_strlen($subjectName) > 255) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.name_too_long')];
        }

        $pdo = Database::getConnection();

        if ($subjectId <= 0) {
            $insert = $pdo->prepare('INSERT INTO ct_materie (uuid, nome_materia) VALUES (:uuid, :nome_materia)');
            $insert->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_materia' => $subjectName,
            ]);

            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.created'), $subjectName)];
        }

        $existing = $this->findSubjectById($subjectId);
        if ($existing === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.not_found')];
        }

        $update = $pdo->prepare('UPDATE ct_materie SET nome_materia = :nome_materia WHERE id_materia = :id_materia');
        $update->execute([
            'nome_materia' => $subjectName,
            'id_materia' => $subjectId,
        ]);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.updated'), $subjectName)];
    }

    public function assignSubject(int $subjectId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $subject = $this->findSubjectById($subjectId);
        if ($subjectId <= 0 || $subject === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.invalid')];
        }

        $exists = Database::getConnection()->prepare(
            'SELECT 1
             FROM ct_utenti_materie
             WHERE fk_utente = :fk_utente AND fk_materia = :fk_materia
             LIMIT 1'
        );
        $exists->execute(['fk_utente' => $userId, 'fk_materia' => $subjectId]);

        if ($exists->fetchColumn() !== false) {
            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.already_assigned'), (string) ($subject['nome_materia'] ?? ''))];
        }

        $insert = Database::getConnection()->prepare(
            'INSERT INTO ct_utenti_materie (fk_utente, fk_materia)
             VALUES (:fk_utente, :fk_materia)'
        );
        $insert->execute(['fk_utente' => $userId, 'fk_materia' => $subjectId]);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.assigned'), (string) ($subject['nome_materia'] ?? ''))];
    }

    public function deleteSubject(int $subjectId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        if ($subjectId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.invalid')];
        }

        $subject = $this->findSubjectById($subjectId);
        if ($subject === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.not_found')];
        }

        $topicCount = $this->countBySubject('ct_argomenti', 'fk_materia', $subjectId);
        $quizCount = $this->countBySubject('ct_quiz', 'fk_materia', $subjectId);

        if ($topicCount > 0 || $quizCount > 0) {
            $reasons = [];
            if ($topicCount > 0) {
                $reasons[] = sprintf($this->tr('testcreator.service.subject.linked_topics'), $topicCount);
            }
            if ($quizCount > 0) {
                $reasons[] = sprintf($this->tr('testcreator.service.subject.linked_quizzes'), $quizCount);
            }

            return [
                'success' => false,
                'message' => sprintf(
                    $this->tr('testcreator.service.subject.delete_blocked'),
                    (string) ($subject['nome_materia'] ?? ''),
                    implode(', ', $reasons)
                ),
            ];
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $deleteAssignments = $pdo->prepare('DELETE FROM ct_utenti_materie WHERE fk_materia = :fk_materia');
            $deleteAssignments->execute(['fk_materia' => $subjectId]);

            $deleteSubject = $pdo->prepare('DELETE FROM ct_materie WHERE id_materia = :id_materia');
            $deleteSubject->execute(['id_materia' => $subjectId]);

            if ($deleteSubject->rowCount() < 1) {
                $pdo->rollBack();
                return ['success' => false, 'message' => $this->tr('testcreator.service.delete_failed')];
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return ['success' => false, 'message' => $this->tr('testcreator.service.delete_unexpected_error')];
        }

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.deleted'), (string) ($subject['nome_materia'] ?? ''))];
    }

    public function unassignSubject(int $subjectId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return [
                'success' => false,
                'message' => $this->tr('testcreator.service.unauthorized'),
            ];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return [
                'success' => false,
                'message' => $this->tr('testcreator.service.user_not_authenticated'),
            ];
        }

        if ($subjectId <= 0) {
            return [
                'success' => false,
                'message' => $this->tr('testcreator.service.subject.invalid'),
            ];
        }

        $subject = $this->findAssignedSubject($userId, $subjectId);
        if ($subject === null) {
            return [
                'success' => false,
                'message' => $this->tr('testcreator.service.subject.not_assigned_current_teacher'),
            ];
        }

        $delete = Database::getConnection()->prepare(
            'DELETE FROM ct_utenti_materie
             WHERE fk_utente = :fk_utente
               AND fk_materia = :fk_materia'
        );

        $delete->execute([
            'fk_utente' => $userId,
            'fk_materia' => $subjectId,
        ]);

        if ($delete->rowCount() < 1) {
            return [
                'success' => false,
                'message' => $this->tr('testcreator.service.subject.unassign_failed'),
            ];
        }

        return [
            'success' => true,
            'message' => sprintf($this->tr('testcreator.service.subject.unassigned'), (string) ($subject['nome_materia'] ?? '')),
        ];
    }

    public function getTopicsPageData(int $selectedSubjectId = 0): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'subjects' => [],
            'topics' => [],
            'selectedSubjectId' => 0,
            'userDisplayName' => '',
            'isAdmin' => false,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $subjects = $this->getAssignedSubjects($userId);
        $isAdmin = $this->isAdmin($userId);
        if ($isAdmin) {
            $subjects = $this->getAllSubjects();
        }

        $validSubjectIds = array_map(static fn (array $subject): int => (int) ($subject['id_materia'] ?? 0), $subjects);
        if ($selectedSubjectId <= 0 || !in_array($selectedSubjectId, $validSubjectIds, true)) {
            $selectedSubjectId = (int) ($validSubjectIds[0] ?? 0);
        }

        $data['subjects'] = $subjects;
        $data['topics'] = $this->getTopicsBySubjects($validSubjectIds);
        $data['selectedSubjectId'] = $selectedSubjectId;
        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['isAdmin'] = $isAdmin;

        return $data;
    }

    public function saveTopic(int $topicId, int $subjectId, string $topicName): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $topicName = trim($topicName);
        if ($topicName === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.name_required')];
        }

        if (mb_strlen($topicName) > 255) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.name_too_long')];
        }

        $isAdmin = $this->isAdmin($userId);
        $allowedSubject = $isAdmin ? $this->findSubjectById($subjectId) : $this->findAssignedSubject($userId, $subjectId);
        if ($subjectId <= 0 || $allowedSubject === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.invalid_current_teacher')];
        }

        if ($topicId <= 0) {
            $insert = Database::getConnection()->prepare(
                'INSERT INTO ct_argomenti (uuid, nome_argomento, fk_materia)
                 VALUES (:uuid, :nome_argomento, :fk_materia)'
            );
            $insert->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_argomento' => $topicName,
                'fk_materia' => $subjectId,
            ]);

            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.topic.created'), $topicName)];
        }

        $topic = $this->findTopicByIdForUser($topicId, $userId, $isAdmin);
        if ($topic === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.not_editable')];
        }

        $update = Database::getConnection()->prepare(
            'UPDATE ct_argomenti
             SET nome_argomento = :nome_argomento,
                 fk_materia = :fk_materia
             WHERE id_argomento = :id_argomento'
        );
        $update->execute([
            'nome_argomento' => $topicName,
            'fk_materia' => $subjectId,
            'id_argomento' => $topicId,
        ]);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.topic.updated'), $topicName)];
    }

    public function deleteTopic(int $topicId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        if ($topicId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.invalid')];
        }

        $topic = $this->findTopicByIdForUser($topicId, $userId, true);
        if ($topic === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.not_found')];
        }

        $materialsCount = $this->countBySubject('ct_materiali', 'fk_argomento', $topicId);
        $exercisesCount = $this->countBySubject('ct_esercizi', 'fk_argomento', $topicId);
        if ($materialsCount > 0 || $exercisesCount > 0) {
            $reasons = [];
            if ($materialsCount > 0) {
                $reasons[] = $materialsCount . ' materiali collegati';
            }
            if ($exercisesCount > 0) {
                $reasons[] = $exercisesCount . ' esercizi collegati';
            }

            return [
                'success' => false,
                'message' => sprintf(
                    $this->tr('testcreator.service.topic.delete_blocked'),
                    (string) ($topic['nome_argomento'] ?? ''),
                    implode(', ', $reasons)
                ),
            ];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $deleteQuestions = $pdo->prepare('DELETE FROM ct_domande WHERE fk_argomento = :fk_argomento');
            $deleteQuestions->execute(['fk_argomento' => $topicId]);

            $deleteTopic = $pdo->prepare('DELETE FROM ct_argomenti WHERE id_argomento = :id_argomento');
            $deleteTopic->execute(['id_argomento' => $topicId]);

            if ($deleteTopic->rowCount() < 1) {
                $pdo->rollBack();
                return ['success' => false, 'message' => $this->tr('testcreator.service.delete_failed')];
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return ['success' => false, 'message' => $this->tr('testcreator.service.delete_unexpected_error')];
        }

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.topic.deleted'), (string) ($topic['nome_argomento'] ?? ''))];
    }

    public function buildSubjectExportPayload(int $subjectId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $isAdmin = $this->isAdmin($userId);
        $subject = $isAdmin ? $this->findSubjectById($subjectId) : $this->findAssignedSubject($userId, $subjectId);
        if ($subjectId <= 0 || $subject === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.invalid_or_inaccessible')];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_argomento, uuid, nome_argomento
             FROM ct_argomenti
             WHERE fk_materia = :fk_materia
             ORDER BY nome_argomento ASC'
        );
        $stmt->execute(['fk_materia' => $subjectId]);
        $topics = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return [
            'success' => true,
            'subjectName' => (string) ($subject['nome_materia'] ?? 'materia'),
            'payload' => [
                'exported_at' => date('c'),
                'subject' => [
                    'uuid' => (string) ($subject['uuid'] ?? ''),
                    'name' => (string) ($subject['nome_materia'] ?? ''),
                ],
                'topics' => array_map(static fn (array $topic): array => [
                    'uuid' => (string) ($topic['uuid'] ?? ''),
                    'name' => (string) ($topic['nome_argomento'] ?? ''),
                ], $topics),
            ],
        ];
    }

    public function importSubjectFromJson(array $files): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        $upload = is_array($files['subject_json'] ?? null) ? $files['subject_json'] : [];
        if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.import.json_required')];
        }
        $tmpName = (string) ($upload['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.import.invalid_file')];
        }

        $payload = json_decode((string) file_get_contents($tmpName), true);
        if (!is_array($payload)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.import.invalid_format')];
        }

        $subject = (array) ($payload['subject'] ?? []);
        $subjectName = trim((string) ($subject['name'] ?? ''));
        if ($subjectName === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.import.missing_subject_name')];
        }
        $subjectUuid = trim((string) ($subject['uuid'] ?? ''));

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $subjectId = $this->findOrCreateSubjectFromImport($pdo, $subjectName, $subjectUuid);
            $importedTopics = 0;

            foreach ((array) ($payload['topics'] ?? []) as $topicRow) {
                $topicName = trim((string) ($topicRow['name'] ?? ''));
                if ($topicName === '') {
                    continue;
                }
                $topicUuid = trim((string) ($topicRow['uuid'] ?? ''));
                $this->findOrCreateTopicFromImport($pdo, $subjectId, $topicName, $topicUuid);
                $importedTopics++;
            }

            $pdo->commit();
            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.subject.import.success'), $importedTopics)];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.subject.import.failed_prefix') . $exception->getMessage()];
        }
    }

    public function getQuestionTopicsPageData(int $selectedSubjectId = 0): array
    {
        $data = $this->getTopicsPageData($selectedSubjectId);
        return [
            'permissionStatus' => $data['permissionStatus'] ?? PermissionService::STATUS_NOT_LOGGED,
            'subjects' => $data['subjects'] ?? [],
            'topics' => $data['topics'] ?? [],
            'selectedSubjectId' => (int) ($data['selectedSubjectId'] ?? 0),
            'userDisplayName' => (string) ($data['userDisplayName'] ?? ''),
        ];
    }

    public function getImportQuestionsMenuPageData(int $selectedSubjectId = 0): array
    {
        return $this->getQuestionTopicsPageData($selectedSubjectId);
    }

    public function getExportQuestionsPageData(int $selectedSubjectId = 0): array
    {
        return $this->getQuestionTopicsPageData($selectedSubjectId);
    }

    public function buildQuestionsExportCsvRows(array $topicIds): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $isAdmin = $this->isAdmin($userId);
        $topicIds = array_values(array_unique(array_filter(array_map('intval', $topicIds), static fn (int $id): bool => $id > 0)));
        if ($topicIds === []) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.export.select_topic')];
        }

        $allowedTopicIds = $this->getAllowedTopicIdsForUser($topicIds, $userId, $isAdmin);
        if ($allowedTopicIds === []) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.export.no_topic_accessible')];
        }

        $questionPlaceholders = implode(',', array_fill(0, count($allowedTopicIds), '?'));
        $questionsStmt = Database::getConnection()->prepare(
            sprintf(
                'SELECT d.id_domanda, d.domanda, d.fk_tipo_domanda, d.num_righe, d.ese_num, d.punti, a.nome_argomento
                 FROM ct_domande d
                 INNER JOIN ct_argomenti a ON a.id_argomento = d.fk_argomento
                 WHERE d.fk_argomento IN (%s)
                   AND d.fk_tipo_domanda IN (1, 2, 3, 4)
                 ORDER BY d.fk_argomento ASC, d.id_domanda ASC',
                $questionPlaceholders
            )
        );
        $questionsStmt->execute($allowedTopicIds);
        $questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $answerStmt = Database::getConnection()->prepare(
            'SELECT risposta, corretta
             FROM ct_risposte
             WHERE fk_domanda = :fk_domanda
             ORDER BY id_risposta ASC'
        );

        $rows = [[
            'domanda',
            'tipo_domanda',
            'risposta_1',
            'risposta_2',
            'risposta_3',
            'risposta_4',
            'risposta_5',
            'risposta_6',
            'meta',
            'punti',
            'argomento',
        ]];
        foreach ($questions as $question) {
            $questionId = (int) ($question['id_domanda'] ?? 0);
            if ($questionId <= 0) {
                continue;
            }

            $questionType = (int) ($question['fk_tipo_domanda'] ?? 1);
            $typeLabelMap = [
                1 => 'risposta aperta',
                2 => 'scelta multipla',
                3 => 'risposta multipla',
                4 => 'esercizio con numeri',
            ];
            $line = [
                str_replace('&#039;', "'", html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES)),
                $typeLabelMap[$questionType] ?? 'risposta aperta',
            ];

            $meta = '';
            if (in_array($questionType, [2, 3], true)) {
                $answerStmt->execute(['fk_domanda' => $questionId]);
                $answers = $answerStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

                $correctIndexes = [];
                $answerIndex = 0;
                foreach ($answers as $answer) {
                    $answerIndex++;
                    $line[] = str_replace('&#039;', "'", html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES));
                    if ((int) ($answer['corretta'] ?? 0) === 1) {
                        $correctIndexes[] = $answerIndex;
                    }
                }

                if ($correctIndexes === []) {
                    continue;
                }

                $meta = $questionType === 2
                    ? (string) ($correctIndexes[0] ?? 1)
                    : implode(',', $correctIndexes);
            }

            while (count($line) < 8) {
                $line[] = '';
            }

            if ($questionType === 1) {
                $meta = (string) max(1, (int) ($question['num_righe'] ?? 1));
            } elseif ($questionType === 4) {
                $meta = str_replace('&#039;', "'", html_entity_decode((string) ($question['ese_num'] ?? ''), ENT_QUOTES));
            }

            $line[] = $meta;
            $line[] = (string) max(1, (float) ($question['punti'] ?? 1));
            $line[] = str_replace('&#039;', "'", html_entity_decode((string) ($question['nome_argomento'] ?? ''), ENT_QUOTES));
            $rows[] = $line;
        }

        if (count($rows) === 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.export.no_questions')];
        }

        return ['success' => true, 'rows' => $rows];
    }

    public function getImportQuestionsFromOtherTeachersPageData(int $topicId): array
    {
        $base = $this->getQuestionsByTopicPageData($topicId);
        if (($base['topic'] ?? null) === null) {
            return $base;
        }

        $permissionService = new PermissionService();
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $base['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $base;
        }

        $base['importableQuestions'] = $this->getImportableQuestionsByTopicForUser($topicId, $userId);
        return $base;
    }

    public function importQuestionFromOtherTeacher(int $questionId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }

        $question = $this->findImportableQuestionByIdForUser($questionId, $userId);
        if ($question === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.not_available_import'), 'redirect' => '/testcreator/import-domande'];
        }

        $topicId = (int) ($question['fk_argomento'] ?? 0);

        $insert = Database::getConnection()->prepare(
            'INSERT INTO ct_utente_domande (fk_utente, fk_domanda)
             VALUES (:fk_utente, :fk_domanda)'
        );
        $insert->execute([
            'fk_utente' => $userId,
            'fk_domanda' => $questionId,
        ]);

        return [
            'success' => true,
            'message' => $this->tr('testcreator.service.question.imported'),
            'redirect' => '/testcreator/import-domande/argomenti/' . $topicId . '/altri-docenti',
        ];
    }

    public function getQuestionAnswersPreview(int $questionId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $question = $this->findImportableQuestionByIdForUser($questionId, $userId);
        if ($question === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.not_available_preview')];
        }

        $answers = $this->getAnswersForQuestion($questionId);
        $normalizedAnswers = array_map(static function (array $answer): array {
            return [
                'id_risposta' => (int) ($answer['id_risposta'] ?? 0),
                'risposta' => html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'corretta' => (int) ($answer['corretta'] ?? 0),
            ];
        }, $answers);

        return [
            'success' => true,
            'answers' => $normalizedAnswers,
        ];
    }

    public function getImportQuestionsFromCsvPageData(int $topicId): array
    {
        if ($topicId > 0) {
            return $this->getQuestionsByTopicPageData($topicId);
        }

        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        return [
            'permissionStatus' => $permissionStatus,
            'topic' => null,
            'questions' => [],
            'userDisplayName' => '',
        ];
    }

    public function importQuestionsFromCsv(int $topicId, array $uploadedFile): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }

        $isAdmin = $this->isAdmin($userId);
        $topic = $topicId > 0 ? $this->findTopicByIdForUser($topicId, $userId, $isAdmin) : null;
        if ($topicId > 0 && $topic === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.not_found_or_inaccessible'), 'redirect' => '/testcreator/import-domande'];
        }

        $redirect = $topicId > 0 ? '/testcreator/import-domande/argomenti/' . $topicId . '/csv' : '/testcreator/import-domande/csv';

        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.no_file'), 'redirect' => $redirect];
        }

        $originalName = (string) ($uploadedFile['name'] ?? '');
        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        $size = (int) ($uploadedFile['size'] ?? 0);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.only_csv'), 'redirect' => $redirect];
        }

        if ($size > self::QUESTIONS_CSV_MAX_SIZE) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.too_large'), 'redirect' => $redirect];
        }

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.invalid_upload'), 'redirect' => $redirect];
        }

        $handle = fopen($tmpName, 'rb');
        if ($handle === false) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.read_failed'), 'redirect' => $redirect];
        }

        $pdo = Database::getConnection();
        $importedCount = 0;
        $skippedCount = 0;
        $topicIdByName = $topicId > 0 ? [] : $this->getAccessibleTopicIdsByNameForUser($userId, $isAdmin);

        try {
            $firstRow = fgetcsv($handle, 0, ';');
            $pendingRows = [];
            if (is_array($firstRow) && !$this->isCsvQuestionHeaderRow($firstRow)) {
                $pendingRows[] = $firstRow;
            }

            while ($pendingRows !== [] || (($row = fgetcsv($handle, 0, ';')) !== false)) {
                if ($pendingRows !== []) {
                    $row = array_shift($pendingRows);
                }

                if ($this->isCsvQuestionRowEmpty($row)) {
                    continue;
                }

                $parsed = $this->parseCsvQuestionRow($row);
                if ($parsed === null) {
                    $skippedCount++;
                    continue;
                }

                $rowTopicId = $topicId;
                if ($rowTopicId <= 0) {
                    $topicName = $this->normalizeCsvTopicName((string) ($parsed['argomento'] ?? ''));
                    $rowTopicId = $topicIdByName[$topicName] ?? 0;
                }

                if ($rowTopicId <= 0) {
                    $skippedCount++;
                    continue;
                }

                $pdo->beginTransaction();
                try {
                    $questionInsert = $pdo->prepare(
                        'INSERT INTO ct_domande (domanda, punti, fk_argomento, fk_tipo_domanda, num_righe, num_gruppo, fk_libro, fk_utente, data_creazione, ese_num, livello_diff)
                         VALUES (:domanda, :punti, :fk_argomento, :fk_tipo_domanda, :num_righe, :num_gruppo, :fk_libro, :fk_utente, :data_creazione, :ese_num, :livello_diff)'
                    );
                    $questionInsert->execute([
                        'domanda' => htmlentities($parsed['domanda'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                        'punti' => $parsed['punti'],
                        'fk_argomento' => $rowTopicId,
                        'fk_tipo_domanda' => $parsed['tipo_domanda'],
                        'num_righe' => $parsed['num_righe'],
                        'num_gruppo' => 0,
                        'fk_libro' => 1,
                        'fk_utente' => $userId,
                        'data_creazione' => date('Y-m-d'),
                        'ese_num' => htmlentities($parsed['ese_num'],ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                        'livello_diff' => 3,
                    ]);
                    $questionId = (int) $pdo->lastInsertId();

                    $bindInsert = $pdo->prepare(
                        'INSERT INTO ct_utente_domande (fk_utente, fk_domanda)
                         VALUES (:fk_utente, :fk_domanda)'
                    );
                    $bindInsert->execute([
                        'fk_utente' => $userId,
                        'fk_domanda' => $questionId,
                    ]);

                    if (in_array($parsed['tipo_domanda'], [2, 3], true)) {
                        $answerInsert = $pdo->prepare(
                            'INSERT INTO ct_risposte (risposta, corretta, fk_domanda)
                             VALUES (:risposta, :corretta, :fk_domanda)'
                        );
                        foreach ($parsed['risposte'] as $index => $answer) {
                            $answerIndex = $index + 1;
                            $answerInsert->execute([
                                'risposta' => htmlentities($answer, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                                'corretta' => in_array($answerIndex, $parsed['corretta_indexes'], true) ? 1 : 0,
                                'fk_domanda' => $questionId,
                            ]);
                        }
                    }

                    $pdo->commit();
                    $importedCount++;
                } catch (Throwable $exception) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $skippedCount++;
                }
            }
        } finally {
            fclose($handle);
        }

        if ($importedCount === 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.csv.no_questions_imported'), 'redirect' => $redirect];
        }

        $message = 'Import CSV completato: ' . $importedCount . ' domande importate';
        if ($skippedCount > 0) {
            $message .= ', ' . $skippedCount . ' righe ignorate';
        }
        $message .= '.';

        return ['success' => true, 'message' => $message, 'redirect' => $redirect];
    }

    public function getQuestionsByTopicPageData(int $topicId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'topic' => null,
            'questions' => [],
            'userDisplayName' => '',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $topic = $this->findTopicByIdForUser($topicId, $userId, $this->isAdmin($userId));
        if ($topic === null) {
            return $data;
        }

        $data['topic'] = $topic;
        $data['questions'] = $this->getQuestionsByTopicForUser($topicId, $userId);
        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        return $data;
    }

    public function getQuestionFormPageData(int $topicId): array
    {
        $base = $this->getQuestionsByTopicPageData($topicId);
        $base['question'] = null;
        $base['books'] = $this->getBooks();
        $base['questionTypes'] = $this->getQuestionTypes();
        $base['answers'] = [];
        $base['formAction'] = '/testcreator/domande/save';
        $base['uploadImageUrl'] = '/testcreator/domande/api/editor/upload-image';
        return $base;
    }

    public function getQuestionEditPageData(int $questionId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'question' => null,
            'topic' => null,
            'books' => [],
            'questionTypes' => [],
            'answers' => [],
            'userDisplayName' => '',
            'formAction' => '',
            'uploadImageUrl' => '/testcreator/domande/api/editor/upload-image',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $question = $this->findQuestionByIdForUser($questionId, $userId);
        if ($question === null) {
            return $data;
        }

        $topic = $this->findTopicByIdForUser((int) ($question['fk_argomento'] ?? 0), $userId, $this->isAdmin($userId));
        if ($topic === null) {
            return $data;
        }

        $data['question'] = $question;
        $data['topic'] = $topic;
        $data['books'] = $this->getBooks();
        $data['questionTypes'] = $this->getQuestionTypes();
        $data['answers'] = $this->getAnswersForQuestion((int) ($question['id_domanda'] ?? 0));
        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['formAction'] = '/testcreator/domande/' . (int) ($question['id_domanda'] ?? 0) . '/update';
        return $data;
    }

    public function saveQuestion(array $input): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }

        $topicId = (int) ($input['id_argomento'] ?? 0);
        $topic = $this->findTopicByIdForUser($topicId, $userId, $this->isAdmin($userId));
        if ($topic === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.topic.invalid'), 'redirect' => '/testcreator/domande'];
        }

        $payload = $this->normalizeQuestionPayload($input);
        $validation = $this->validateQuestionPayload($payload);
        if ($validation !== null) {
            return ['success' => false, 'message' => $validation, 'redirect' => '/testcreator/domande/argomenti/' . $topicId . '/nuova'];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare(
                'INSERT INTO ct_domande (domanda, punti, fk_argomento, fk_tipo_domanda, num_righe, num_gruppo, fk_libro, fk_utente, data_creazione, ese_num, livello_diff)
                 VALUES (:domanda, :punti, :fk_argomento, :fk_tipo_domanda, :num_righe, :num_gruppo, :fk_libro, :fk_utente, :data_creazione, :ese_num, :livello_diff)'
            );
            $stmt->execute([
                'domanda' => htmlentities($payload['domanda'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'punti' => $payload['punti'],
                'fk_argomento' => $topicId,
                'fk_tipo_domanda' => $payload['tipo_domanda'],
                'num_righe' => $payload['num_righe'],
                'num_gruppo' => $payload['num_gruppo'],
                'fk_libro' => $payload['fk_libro'],
                'fk_utente' => $userId,
                'data_creazione' => date('Y-m-d'),
                'ese_num' => htmlentities($payload['ese_num'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'livello_diff' => $payload['livello_diff'],
            ]);
            $questionId = (int) $pdo->lastInsertId();

            $bind = $pdo->prepare('INSERT INTO ct_utente_domande (fk_utente, fk_domanda) VALUES (:fk_utente, :fk_domanda)');
            $bind->execute(['fk_utente' => $userId, 'fk_domanda' => $questionId]);

            $this->saveAnswers($pdo, $questionId, $payload);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.save_error'), 'redirect' => '/testcreator/domande/argomenti/' . $topicId . '/nuova'];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.question.saved'), 'redirect' => '/testcreator/domande/argomenti/' . $topicId];
    }

    public function updateQuestion(array $input): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }

        $questionId = (int) ($input['id_domanda'] ?? 0);
        $question = $this->findQuestionByIdForUser($questionId, $userId);
        if ($question === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.invalid'), 'redirect' => '/testcreator/domande'];
        }

        $topicId = (int) ($question['fk_argomento'] ?? 0);
        $payload = $this->normalizeQuestionPayload($input, false);
        $payload['tipo_domanda'] = (int) ($question['fk_tipo_domanda'] ?? 1);
        $validation = $this->validateQuestionPayload($payload);
        if ($validation !== null) {
            return ['success' => false, 'message' => $validation, 'redirect' => '/testcreator/domande/' . $questionId . '/modifica'];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $update = $pdo->prepare(
                'UPDATE ct_domande
                 SET domanda = :domanda, punti = :punti, num_righe = :num_righe, num_gruppo = :num_gruppo, fk_libro = :fk_libro, ese_num = :ese_num, livello_diff = :livello_diff
                 WHERE id_domanda = :id_domanda'
            );
            $update->execute([
                'domanda' => htmlentities($payload['domanda'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'punti' => $payload['punti'],
                'num_righe' => $payload['num_righe'],
                'num_gruppo' => $payload['num_gruppo'],
                'fk_libro' => $payload['fk_libro'],
                'ese_num' => htmlentities($payload['ese_num'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'livello_diff' => $payload['livello_diff'],
                'id_domanda' => $questionId,
            ]);

            $deleteAnswers = $pdo->prepare('DELETE FROM ct_risposte WHERE fk_domanda = :fk_domanda');
            $deleteAnswers->execute(['fk_domanda' => $questionId]);
            $this->saveAnswers($pdo, $questionId, $payload);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.save_error'), 'redirect' => '/testcreator/domande/' . $questionId . '/modifica'];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.question.updated'), 'redirect' => '/testcreator/domande/argomenti/' . $topicId];
    }

    public function removeQuestionAssignment(int $questionId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_utente_domande
             WHERE fk_domanda = :fk_domanda AND fk_utente = :fk_utente'
        );
        $stmt->execute(['fk_domanda' => $questionId, 'fk_utente' => $userId]);
        if ($stmt->rowCount() < 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.not_assigned_current_teacher')];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.question.removed_from_archive')];
    }

    public function deleteQuestionPermanently(int $questionId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $question = $this->findQuestionOwnedByUser($questionId, $userId);
        if ($question === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.delete_own_only')];
        }

        if ($this->countBySubject('ct_utente_domande', 'fk_domanda', $questionId) > 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.used_by_others')];
        }
        if ($this->countBySubject('ct_quiz_domande', 'fk_domanda', $questionId) > 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.linked_quiz')];
        }
        if ($this->countBySubject('ct_esercizio_domande', 'fk_domanda', $questionId) > 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.linked_exercise')];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM ct_utente_domande WHERE fk_domanda = :fk_domanda')->execute(['fk_domanda' => $questionId]);
            $pdo->prepare('DELETE FROM ct_risposte WHERE fk_domanda = :fk_domanda')->execute(['fk_domanda' => $questionId]);
            $pdo->prepare('DELETE FROM ct_domande WHERE id_domanda = :id_domanda')->execute(['id_domanda' => $questionId]);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.question.delete_permanent_error')];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.question.deleted_permanently')];
    }

    public function uploadQuestionEditorImage(array $files): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $uploadedFile = is_array($files['file'] ?? null) ? $files['file'] : [];
        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.image.invalid')];
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.image.invalid_file')];
        }

        $originalName = (string) ($uploadedFile['name'] ?? 'question.png');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed, true)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.image.unsupported_format')];
        }

        $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Questions';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.image.folder_failed')];
        }

        $targetName = uniqid('question_editor_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $targetName;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.image.save_failed')];
        }

        return ['location' => '/assets/images/Questions/' . $targetName];
    }


    public function getPrintTemplatesPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'templates' => [],
            'selectedTemplate' => null,
            'userDisplayName' => '',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['templates'] = $this->getPrintTemplateDirectories();
        $data['selectedTemplate'] = $this->getUserPrintTemplate($userId);
        $data['userDisplayName'] = $this->getUserDisplayName($userId);

        return $data;
    }

    public function selectPrintTemplate(string $templateName): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.invalid_session')];
        }

        $templateName = trim($templateName);
        if ($templateName === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.invalid')];
        }

        if (!in_array($templateName, $this->getPrintTemplateDirectories(), true)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.not_found')];
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_utenti
             SET template_stampa = :template_stampa
             WHERE id_utente = :id_utente'
        );
        $stmt->execute([
            'template_stampa' => $templateName,
            'id_utente' => $userId,
        ]);

        return ['success' => true, 'message' => $this->tr('testcreator.service.template.selected')];
    }

    public function uploadPrintTemplate(array $files): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $uploadedFile = is_array($files['file'] ?? null) ? $files['file'] : [];
        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.file_not_uploaded')];
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        $originalName = (string) ($uploadedFile['name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.file_not_uploaded')];
        }

        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension !== 'zip') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.invalid_zip_format')];
        }

        $tmpDir = sys_get_temp_dir() . '/upload_' . uniqid('', true);
        if (!mkdir($tmpDir, 0775, true) && !is_dir($tmpDir)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.temp_folder_failed')];
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.unzip_failed')];
        }

        if (!$zip->extractTo($tmpDir)) {
            $zip->close();
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.unzip_failed')];
        }
        $zip->close();

        $items = array_values(array_diff(scandir($tmpDir) ?: [], ['.', '..']));
        if (count($items) !== 1 || !is_dir($tmpDir . '/' . $items[0])) {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.single_folder_required')];
        }

        $folderName = (string) $items[0];
        $normalizedFolder = preg_replace('/\s+/', '', $folderName) ?? '';
        $normalizedFolder = trim(str_replace(['/', '\\'], '', $normalizedFolder));
        if ($normalizedFolder === '') {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.rename_failed')];
        }

        $oldFolderPath = $tmpDir . DIRECTORY_SEPARATOR . $folderName;
        $newFolderPath = $tmpDir . DIRECTORY_SEPARATOR . $normalizedFolder;

        if ($normalizedFolder !== $folderName && !rename($oldFolderPath, $newFolderPath)) {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.rename_failed')];
        }

        $destDir = dirname(__DIR__, 2) . '/public/assets/template';
        if (!is_dir($destDir) && !mkdir($destDir, 0775, true) && !is_dir($destDir)) {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.destination_folder_failed')];
        }

        $finalPath = $destDir . DIRECTORY_SEPARATOR . $normalizedFolder;
        if (file_exists($finalPath)) {
            $this->deleteDirectory($tmpDir);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.duplicate_folder')];
        }

        $sourcePath = is_dir($newFolderPath) ? $newFolderPath : $oldFolderPath;
        if (!$this->copyDirectory($sourcePath, $finalPath)) {
            $this->deleteDirectory($tmpDir);
            $this->deleteDirectory($finalPath);
            return ['success' => false, 'message' => $this->tr('testcreator.service.template.save_error')];
        }

        $this->deleteDirectory($tmpDir);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.template.uploaded'), $normalizedFolder)];
    }

    public function getBooksManagementPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'books' => [],
            'userDisplayName' => '',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['books'] = $this->getBooks();
        $data['userDisplayName'] = $this->getUserDisplayName($userId);

        return $data;
    }

    public function getBookFormData(int $bookId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        if ($bookId <= 0) {
            return [
                'success' => true,
                'book' => [
                    'id_libro_testo' => 0,
                    'titolo_libro' => '',
                    'casa_editrice' => '',
                    'autori' => '',
                ],
            ];
        }

        $book = $this->findBookById($bookId);
        if ($book === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.not_found')];
        }

        return ['success' => true, 'book' => $book];
    }

    public function saveBook(int $bookId, string $title, string $publisher, string $authors): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $title = trim($title);
        $publisher = trim($publisher);
        $authors = trim($authors);

        if ($title === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.title_required')];
        }
        if ($publisher === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.publisher_required')];
        }
        if ($authors === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.authors_required')];
        }

        $pdo = Database::getConnection();
        if ($bookId <= 0) {
            $insert = $pdo->prepare(
                'INSERT INTO ct_libri_testo (titolo_libro, casa_editrice, autori, disattivato)
                 VALUES (:titolo_libro, :casa_editrice, :autori, 0)'
            );
            $insert->execute([
                'titolo_libro' => $title,
                'casa_editrice' => $publisher,
                'autori' => $authors,
            ]);

            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.book.created'), $title)];
        }

        $existing = $this->findBookById($bookId);
        if ($existing === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.not_found')];
        }

        $update = $pdo->prepare(
            'UPDATE ct_libri_testo
             SET titolo_libro = :titolo_libro,
                 casa_editrice = :casa_editrice,
                 autori = :autori
             WHERE id_libro_testo = :id_libro_testo'
        );
        $update->execute([
            'titolo_libro' => $title,
            'casa_editrice' => $publisher,
            'autori' => $authors,
            'id_libro_testo' => $bookId,
        ]);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.book.updated'), $title)];
    }

    public function deactivateBook(int $bookId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        if ($bookId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.invalid')];
        }

        $book = $this->findBookById($bookId);
        if ($book === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.book.not_found')];
        }

        if ((int) ($book['disattivato'] ?? 0) === 1) {
            return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.book.already_disabled'), (string) ($book['titolo_libro'] ?? ''))];
        }

        $update = Database::getConnection()->prepare(
            'UPDATE ct_libri_testo
             SET disattivato = 1
             WHERE id_libro_testo = :id_libro_testo'
        );
        $update->execute(['id_libro_testo' => $bookId]);

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.book.disabled'), (string) ($book['titolo_libro'] ?? ''))];
    }

    public function getGridsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'grids' => [],
            'userDisplayName' => '',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['grids'] = $this->getEvaluationGrids($userId, true);

        return $data;
    }

    public function getGridFormPageData(int $gridId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'gridNotFound' => false,
            'grid' => [
                'id_griglia' => 0,
                'nome_griglia' => '',
                'griglia' => 'Inserire qui la griglia di valutazione',
            ],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        if ($gridId <= 0) {
            return $data;
        }

        $grid = $this->findGridByIdForUser($gridId, $userId);
        if ($grid === null) {
            $data['gridNotFound'] = true;
            return $data;
        }

        $grid['griglia'] = $this->decodeStoredHtml((string) ($grid['griglia'] ?? ''));
        $data['grid'] = $grid;

        return $data;
    }

    public function saveGrid(array $input): array
    {
        return $this->persistGrid($input, false);
    }

    public function updateGrid(array $input): array
    {
        return $this->persistGrid($input, true);
    }

    public function deleteGrid(int $gridId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        if ($gridId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.grid.invalid')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.invalid_session')];
        }

        $grid = $this->findGridByIdForUser($gridId, $userId);
        if ($grid === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.grid.not_found_or_inaccessible')];
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_griglie_valutazione
             SET attiva = 2
             WHERE id_griglia = :id_griglia AND fk_utente = :fk_utente'
        );
        $stmt->execute([
            'id_griglia' => $gridId,
            'fk_utente' => $userId,
        ]);

        return ['success' => true, 'message' => $this->tr('testcreator.service.grid.deleted')];
    }

    public function getQuizzesPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'quizzes' => [],
            'userDisplayName' => '',
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['quizzes'] = $this->getQuizzesForUser($userId);

        return $data;
    }

    public function getQuizFormPageData(int $quizId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'quizNotFound' => false,
            'quiz' => [
                'id_quiz' => 0,
                'nome_quiz' => '',
                'fk_materia' => 0,
                'mostra_punti_dom' => 1,
                'mix_questions' => 0,
                'casuale' => 1,
                'mix_answer' => 1,
                'fk_griglia' => 0,
            ],
            'subjects' => [],
            'topicsBySubject' => [],
            'selectedTopics' => [],
            'questionTypes' => [],
            'selectedTypeRules' => [],
            'grids' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $subjects = $this->getAssignedSubjects($userId);
        $data['subjects'] = $subjects;
        $data['questionTypes'] = $this->getQuestionTypes();
        $data['grids'] = $this->getEvaluationGrids($userId);

        $subjectIds = array_map(static fn (array $subject): int => (int) ($subject['id_materia'] ?? 0), $subjects);
        $data['topicsBySubject'] = $this->getTopicsGroupedBySubject($subjectIds);

        if ($quizId <= 0) {
            if ($subjects !== []) {
                $data['quiz']['fk_materia'] = (int) ($subjects[0]['id_materia'] ?? 0);
            }
            return $data;
        }

        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            $data['quizNotFound'] = true;
            return $data;
        }

        $data['quiz'] = $quiz;
        $data['selectedTopics'] = $this->getQuizTopicIds($quizId);
        $data['selectedTypeRules'] = $this->getQuizTypeRules($quizId);

        return $data;
    }

    public function saveQuiz(array $input): array
    {
        return $this->persistQuiz($input, false);
    }

    public function updateQuiz(array $input): array
    {
        return $this->persistQuiz($input, true);
    }

    public function getQuizGenerationPageData(int $quizId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'quizNotFound' => false,
            'errorMessage' => '',
            'quiz' => null,
            'questions' => [],
        ];
        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }
        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            $data['quizNotFound'] = true;
            return $data;
        }

        $questionIds = $this->extractQuizQuestionIds($quiz, $userId);
        if ($questionIds === []) {
            $data['errorMessage'] = 'Non ci sono domande disponibili per questo quiz.';
            return $data;
        }
        if ((int) ($quiz['mix_questions'] ?? 0) === 0) {
            shuffle($questionIds);
        }

        $_SESSION['array_domande'] = $questionIds;
        $_SESSION['array_esercizi'] = [];

        $data['quiz'] = $quiz;
        $data['questions'] = $this->loadQuestionsWithAnswers($questionIds);
        $_SESSION['array_esercizi'] = $this->buildExerciseTexts($data['questions']);

        return $data;
    }

    public function buildQuizCsvRows(int $quizId): array
    {
        $pageData = $this->getQuizGenerationPageData($quizId);
        if (($pageData['permissionStatus'] ?? null) !== PermissionService::STATUS_OK || ($pageData['quiz'] ?? null) === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.unavailable')];
        }

        $rows = [];
        foreach ($pageData['questions'] as $question) {
            $answers = is_array($question['answers'] ?? null) ? $question['answers'] : [];
            if (count($answers) !== 4) {
                continue;
            }
            $line = [html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES)];
            $line[] = ((int) ($question['fk_tipo_domanda'] ?? 0) === 1) ? 'TEXT' : 'MULTIPLE_CHOICE';
            $correct = '';
            foreach ($answers as $answer) {
                $answerText = html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES);
                $line[] = $answerText;
                if ((int) ($answer['corretta'] ?? 0) === 1) {
                    $correct = $answerText;
                }
            }
            if ($correct === '') {
                continue;
            }
            $line[] = $correct;
            $rows[] = $line;
        }

        return ['success' => true, 'rows' => $rows];
    }

    public function buildQuizPdf(int $quizId, array $options = []): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }
        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.not_found_or_inaccessible')];
        }

        $questionIds = $this->getStoredOrGeneratedQuizQuestionIds($quiz, $userId);
        if ($questionIds === []) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.no_print_questions')];
        }
        $questions = $this->loadQuestionsWithAnswers($questionIds);
        $exerciseTexts = is_array($_SESSION['array_esercizi'] ?? null) ? $_SESSION['array_esercizi'] : $this->buildExerciseTexts($questions);
        $_SESSION['array_domande'] = $questionIds;
        $_SESSION['array_esercizi'] = $exerciseTexts;

        if (($options['cocorrezione'] ?? false) === true) {
            return $this->buildCoCorrectionPdf($quizId, $quiz, $options);
        }

        $renderedQuestions = $this->prepareQuestionsForRender($questions, $exerciseTexts, $options);
        $gridHtml = '';
        $gridId = (int) ($quiz['fk_griglia'] ?? 0);
        if ($gridId > 0) {
            $grid = $this->findGridByIdForUser($gridId, $userId);
            if ($grid !== null) {
                $gridHtml = $this->decodeStoredHtml((string) ($grid['griglia'] ?? ''));
            }
        }

        $printPayload = $this->buildQuizHtml($userId, $quiz, $renderedQuestions, (($options['dsa'] ?? false) === true), $gridHtml);

        try {
            $mpdfConfig = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 12,
                'margin_right' => 12,
                'margin_top' => 12,
                'margin_bottom' => 12,
                'default_font' => 'helvetica'
            ];
            if (($options['opendyslexic'] ?? false) === true) {
                $fontDir = dirname(__DIR__, 2) . '/vendor/mpdf/mpdf/ttfonts';
                $fontFile = $fontDir . '/OpenDyslexic-Regular.otf';
                if (is_file($fontFile)) {
                    $mpdfConfig['fontDir'] = [$fontDir];
                    $mpdfConfig['fontdata'] = ['opendyslexic' => ['R' => 'OpenDyslexic-Regular.otf']];
                    $mpdfConfig['default_font'] = 'opendyslexic';
                }
            }
            //echo $html;
            $mpdf = new \Mpdf\Mpdf($mpdfConfig);
            if (($printPayload['header'] ?? '') !== '') {
                $mpdf->SetHTMLHeader((string) $printPayload['header']);
            }
            if (($printPayload['footer'] ?? '') !== '') {
                $mpdf->SetHTMLFooter((string) $printPayload['footer']);
            }
            $mpdf->WriteHTML((string) ($printPayload['html'] ?? ''));
            return ['success' => true, 'pdf' => $mpdf->Output('', 'S')];
        } catch (Throwable $exception) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.pdf.generate_error_prefix') . $exception->getMessage()];
        }
    }

    public function getManualQuestionSelectionPageData(int $quizId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();
        $data = [
            'permissionStatus' => $permissionStatus,
            'quizNotFound' => false,
            'quiz' => null,
            'questions' => [],
            'selectedQuestionIds' => [],
        ];
        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }
        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            $data['quizNotFound'] = true;
            return $data;
        }

        $topicIds = $this->getQuizTopicIds($quizId);
        $data['quiz'] = $quiz;
        $data['selectedQuestionIds'] = $this->getQuizQuestionIds($quizId);
        $data['questions'] = $this->getQuestionsByTopicsForUser($topicIds, $userId);
        return $data;
    }

    public function saveManualQuestionSelection(int $quizId, array $selectedQuestionIds): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }
        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }
        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.not_found'), 'redirect' => '/testcreator/quiz'];
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $selectedQuestionIds), static fn (int $id): bool => $id > 0)));
        $allowedIds = array_map(static fn (array $q): int => (int) ($q['id_domanda'] ?? 0), $this->getQuestionsByTopicsForUser($this->getQuizTopicIds($quizId), $userId));
        $allowedMap = array_fill_keys($allowedIds, true);
        $finalIds = array_values(array_filter($ids, fn (int $id): bool => isset($allowedMap[$id])));

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM ct_quiz_domande WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            $insert = $pdo->prepare('INSERT INTO ct_quiz_domande (fk_quiz, fk_domanda) VALUES (:fk_quiz, :fk_domanda)');
            foreach ($finalIds as $questionId) {
                $insert->execute(['fk_quiz' => $quizId, 'fk_domanda' => $questionId]);
            }
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.questions_save_error'), 'redirect' => '/testcreator/quiz/' . $quizId . '/domande-selezione'];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.quiz.questions_saved'), 'redirect' => '/testcreator/quiz'];
    }

    public function deleteQuiz(int $quizId): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated')];
        }

        $quiz = $this->findQuizByIdForUser($quizId, $userId);
        if ($quiz === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.not_found_or_inaccessible')];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM ct_quiz_domande WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            $pdo->prepare('DELETE FROM ct_quiz_tipo_domande WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            $pdo->prepare('DELETE FROM ct_quiz_argomenti WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            $pdo->prepare('DELETE FROM ct_quiz WHERE id_quiz = :id_quiz')->execute(['id_quiz' => $quizId]);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.delete_error')];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.quiz.deleted')];
    }

    private function persistQuiz(array $input, bool $isUpdate): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.user_not_authenticated'), 'redirect' => '/'];
        }

        $quizId = $isUpdate ? (int) ($input['id_quiz'] ?? 0) : 0;
        if ($isUpdate && $quizId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.invalid'), 'redirect' => '/testcreator/quiz'];
        }

        $quiz = null;
        if ($isUpdate) {
            $quiz = $this->findQuizByIdForUser($quizId, $userId);
            if ($quiz === null) {
                return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.not_found_or_inaccessible'), 'redirect' => '/testcreator/quiz'];
            }
        }

        $payload = $this->normalizeQuizPayload($input);
        $validationError = $this->validateQuizPayload($payload, $userId);
        $redirectFallback = $isUpdate ? '/testcreator/quiz/' . $quizId . '/modifica' : '/testcreator/quiz/nuovo';
        if ($validationError !== null) {
            return ['success' => false, 'message' => $validationError, 'redirect' => $redirectFallback];
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            if ($isUpdate) {
                $update = $pdo->prepare(
                    'UPDATE ct_quiz
                     SET nome_quiz = :nome_quiz,
                         casuale = :casuale,
                         mix_answer = :mix_answer,
                         fk_materia = :fk_materia,
                         mostra_punti_dom = :mostra_punti_dom,
                         fk_griglia = :fk_griglia,
                         mix_questions = :mix_questions
                     WHERE id_quiz = :id_quiz'
                );
                $update->execute([
                    'nome_quiz' => $payload['nome_quiz'],
                    'casuale' => $payload['casuale'],
                    'mix_answer' => $payload['mix_answer'],
                    'fk_materia' => $payload['fk_materia'],
                    'mostra_punti_dom' => $payload['mostra_punti_dom'],
                    'fk_griglia' => $payload['fk_griglia'],
                    'mix_questions' => $payload['mix_questions'],
                    'id_quiz' => $quizId,
                ]);
            } else {
                $insert = $pdo->prepare(
                    'INSERT INTO ct_quiz (nome_quiz, fk_utente, data_creazione, casuale, mix_answer, fk_materia, mostra_punti_dom, fk_griglia, mix_questions)
                     VALUES (:nome_quiz, :fk_utente, :data_creazione, :casuale, :mix_answer, :fk_materia, :mostra_punti_dom, :fk_griglia, :mix_questions)'
                );
                $insert->execute([
                    'nome_quiz' => $payload['nome_quiz'],
                    'fk_utente' => $userId,
                    'data_creazione' => date('Y-m-d'),
                    'casuale' => $payload['casuale'],
                    'mix_answer' => $payload['mix_answer'],
                    'fk_materia' => $payload['fk_materia'],
                    'mostra_punti_dom' => $payload['mostra_punti_dom'],
                    'fk_griglia' => $payload['fk_griglia'],
                    'mix_questions' => $payload['mix_questions'],
                ]);
                $quizId = (int) $pdo->lastInsertId();
            }

            $pdo->prepare('DELETE FROM ct_quiz_argomenti WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            $insertTopic = $pdo->prepare('INSERT INTO ct_quiz_argomenti (fk_quiz, fk_argomento) VALUES (:fk_quiz, :fk_argomento)');
            foreach ($payload['argomenti'] as $topicId) {
                $insertTopic->execute(['fk_quiz' => $quizId, 'fk_argomento' => $topicId]);
            }

            $pdo->prepare('DELETE FROM ct_quiz_tipo_domande WHERE fk_quiz = :fk_quiz')->execute(['fk_quiz' => $quizId]);
            if ($payload['casuale'] === 1) {
                $insertRule = $pdo->prepare(
                    'INSERT INTO ct_quiz_tipo_domande (fk_quiz, fk_tipo_domande, num_domande)
                     VALUES (:fk_quiz, :fk_tipo_domande, :num_domande)'
                );
                
                foreach ($payload['type_rules'] as $rule) {
                    $questionTypeId = (int) ($rule['fk_tipo_domande'] ?? 0);
                    if ($questionTypeId < 0) {
                        continue;
                    }

                    $insertRule->execute([
                        'fk_quiz' => $quizId,
                        'fk_tipo_domande' => $questionTypeId,
                        'num_domande' => $rule['num_domande'],
                    ]);
                }
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => $this->tr('testcreator.service.quiz.save_error_prefix') . $exception->getMessage(), 'redirect' => $redirectFallback];
        }

        $redirectSuccess = '/testcreator/quiz';
        if ($payload['casuale'] === 0) {
            $redirectSuccess = '/testcreator/quiz/' . $quizId . '/domande-selezione';
        }

        return [
            'success' => true,
            'message' => $this->tr($isUpdate ? 'testcreator.service.quiz.updated' : 'testcreator.service.quiz.created'),
            'redirect' => $redirectSuccess,
        ];
    }

    private function getAssignedSubjects(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_materia, m.nome_materia
             FROM ct_utenti_materie um
             INNER JOIN ct_materie m ON m.id_materia = um.fk_materia
             WHERE um.fk_utente = :fk_utente
             ORDER BY m.nome_materia ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAllSubjectsWithAssignmentState(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT
                m.id_materia,
                m.nome_materia,
                CASE WHEN um.fk_utente IS NULL THEN 0 ELSE 1 END AS assegnata
             FROM ct_materie m
             LEFT JOIN ct_utenti_materie um
               ON um.fk_materia = m.id_materia
              AND um.fk_utente = :fk_utente
             ORDER BY m.nome_materia ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAllSubjects(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id_materia, nome_materia
             FROM ct_materie
             ORDER BY nome_materia ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getTopicsBySubjects(array $subjectIds): array
    {
        $subjectIds = array_values(array_filter(array_map('intval', $subjectIds), static fn (int $id): bool => $id > 0));
        if ($subjectIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
        $stmt = Database::getConnection()->prepare(
            sprintf(
                'SELECT a.id_argomento, a.nome_argomento, a.fk_materia, m.nome_materia
                 FROM ct_argomenti a
                 INNER JOIN ct_materie m ON m.id_materia = a.fk_materia
                 WHERE a.fk_materia IN (%s)
                 ORDER BY m.nome_materia ASC, a.nome_argomento ASC',
                $placeholders
            )
        );
        $stmt->execute($subjectIds);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAllowedTopicIdsForUser(array $topicIds, int $userId, bool $isAdmin): array
    {
        if ($topicIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($topicIds), '?'));

        if ($isAdmin) {
            $stmt = Database::getConnection()->prepare(
                sprintf(
                    'SELECT id_argomento
                     FROM ct_argomenti
                     WHERE id_argomento IN (%s)',
                    $placeholders
                )
            );
            $stmt->execute($topicIds);
        } else {
            $params = $topicIds;
            $params[] = $userId;
            $stmt = Database::getConnection()->prepare(
                sprintf(
                    'SELECT a.id_argomento
                     FROM ct_argomenti a
                     INNER JOIN ct_utenti_materie um ON um.fk_materia = a.fk_materia
                     WHERE a.id_argomento IN (%s)
                       AND um.fk_utente = ?',
                    $placeholders
                )
            );
            $stmt->execute($params);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return array_values(array_map(static fn (array $row): int => (int) ($row['id_argomento'] ?? 0), $rows));
    }

    private function getAccessibleTopicIdsByNameForUser(int $userId, bool $isAdmin): array
    {
        if ($isAdmin) {
            $stmt = Database::getConnection()->query(
                'SELECT id_argomento, nome_argomento
                 FROM ct_argomenti
                 ORDER BY id_argomento ASC'
            );
        } else {
            $stmt = Database::getConnection()->prepare(
                'SELECT a.id_argomento, a.nome_argomento
                 FROM ct_argomenti a
                 INNER JOIN ct_utenti_materie um ON um.fk_materia = a.fk_materia
                 WHERE um.fk_utente = :fk_utente
                 ORDER BY a.id_argomento ASC'
            );
            $stmt->execute(['fk_utente' => $userId]);
        }

        $map = [];
        foreach (($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) as $topic) {
            $key = $this->normalizeCsvTopicName((string) ($topic['nome_argomento'] ?? ''));
            if ($key !== '' && !isset($map[$key])) {
                $map[$key] = (int) ($topic['id_argomento'] ?? 0);
            }
        }

        return $map;
    }

    private function findAssignedSubject(int $userId, int $subjectId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_materia, m.nome_materia
             FROM ct_utenti_materie um
             INNER JOIN ct_materie m ON m.id_materia = um.fk_materia
             WHERE um.fk_utente = :fk_utente
               AND um.fk_materia = :fk_materia
             LIMIT 1'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'fk_materia' => $subjectId,
        ]);

        $subject = $stmt->fetch(PDO::FETCH_ASSOC);
        return $subject === false ? null : $subject;
    }

    private function findSubjectById(int $subjectId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_materia, uuid, nome_materia
             FROM ct_materie
             WHERE id_materia = :id_materia
             LIMIT 1'
        );
        $stmt->execute(['id_materia' => $subjectId]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        return $subject === false ? null : $subject;
    }

    private function findTopicByIdForUser(int $topicId, int $userId, bool $isAdmin): ?array
    {
        if ($isAdmin) {
            $stmt = Database::getConnection()->prepare(
                'SELECT a.id_argomento, a.nome_argomento, a.fk_materia
                 FROM ct_argomenti a
                 WHERE a.id_argomento = :id_argomento
                 LIMIT 1'
            );
            $stmt->execute(['id_argomento' => $topicId]);
        } else {
            $stmt = Database::getConnection()->prepare(
                'SELECT a.id_argomento, a.nome_argomento, a.fk_materia
                 FROM ct_argomenti a
                 INNER JOIN ct_utenti_materie um ON um.fk_materia = a.fk_materia
                 WHERE a.id_argomento = :id_argomento
                   AND um.fk_utente = :fk_utente
                 LIMIT 1'
            );
            $stmt->execute([
                'id_argomento' => $topicId,
                'fk_utente' => $userId,
            ]);
        }

        $topic = $stmt->fetch(PDO::FETCH_ASSOC);
        return $topic === false ? null : $topic;
    }

    private function countBySubject(string $table, string $column, int $subjectId): int
    {
        $query = sprintf('SELECT COUNT(*) FROM %s WHERE %s = :subject_id', $table, $column);
        $stmt = Database::getConnection()->prepare($query);
        $stmt->execute(['subject_id' => $subjectId]);

        return (int) $stmt->fetchColumn();
    }

    private function getUserDisplayName(int $userId): string
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT username FROM ct_utenti WHERE id_utente = :id_utente LIMIT 1'
        );
        $stmt->execute(['id_utente' => $userId]);

        $username = $stmt->fetchColumn();
        return is_string($username) ? $username : '';
    }

    private function isAdmin(int $userId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :id_utente
               AND tu.tipo_utente = "amministratore"'
        );
        $stmt->execute(['id_utente' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function guardAdminAction(): ?array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized')];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.admin_only')];
        }

        return null;
    }

    private function getAllowedTeacherEmails(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id_mail_abilitata, mail
             FROM ct_mail_abilitate
             ORDER BY mail ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function extractEmailsFromCsv(string $tmpPath): array
    {
        $rows = @file($tmpPath, FILE_IGNORE_NEW_LINES);
        if ($rows === false) {
            return [];
        }

        $emails = [];
        foreach ($rows as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }

            $fields = str_getcsv($line, ';');
            if (count($fields) === 1) {
                $fields = str_getcsv($line, ',');
            }

            foreach ($fields as $field) {
                $email = mb_strtolower(trim((string) $field));
                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                $emails[] = $email;
            }
        }

        return $emails;
    }

    private function getTeachersWithAdminState(): array
    {
        $adminRoleId = $this->getAdminRoleId();
        $docRoleId = $this->getTeacherRoleId();

        if ($docRoleId <= 0) {
            return [];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT DISTINCT u.id_utente, u.nome, u.cognome, u.username,
                    CASE WHEN admin.fk_utente IS NULL THEN 0 ELSE 1 END AS is_admin
             FROM ct_utenti u
             INNER JOIN ct_utenti_tipi doc ON doc.fk_utente = u.id_utente AND doc.fk_tipo_utente = :doc_role_id
             LEFT JOIN ct_utenti_tipi admin ON admin.fk_utente = u.id_utente AND admin.fk_tipo_utente = :admin_role_id
             ORDER BY u.cognome ASC, u.nome ASC, u.username ASC'
        );
        $stmt->execute([
            'doc_role_id' => $docRoleId,
            'admin_role_id' => $adminRoleId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findTeacherById(int $userId): ?array
    {
        $docRoleId = $this->getTeacherRoleId();
        $adminRoleId = $this->getAdminRoleId();
        if ($docRoleId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT u.id_utente,
                    CASE WHEN admin.fk_utente IS NULL THEN 0 ELSE 1 END AS is_admin
             FROM ct_utenti u
             INNER JOIN ct_utenti_tipi doc ON doc.fk_utente = u.id_utente AND doc.fk_tipo_utente = :doc_role_id
             LEFT JOIN ct_utenti_tipi admin ON admin.fk_utente = u.id_utente AND admin.fk_tipo_utente = :admin_role_id
             WHERE u.id_utente = :id_utente
             LIMIT 1'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'doc_role_id' => $docRoleId,
            'admin_role_id' => $adminRoleId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function getTeacherRoleId(): int
    {
        static $roleId = null;
        if (is_int($roleId)) {
            return $roleId;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_tipo_utente
             FROM ct_tipo_utente
             WHERE tipo_utente = "docente"
             LIMIT 1'
        );
        $stmt->execute();
        $roleId = (int) $stmt->fetchColumn();

        return $roleId;
    }

    private function getAdminRoleId(): int
    {
        static $roleId = null;
        if (is_int($roleId)) {
            return $roleId;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_tipo_utente
             FROM ct_tipo_utente
             WHERE tipo_utente = "amministratore"
             LIMIT 1'
        );
        $stmt->execute();
        $roleId = (int) $stmt->fetchColumn();

        return $roleId;
    }

    private function getQuestionsByTopicForUser(int $topicId, int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.id_domanda, d.domanda, d.fk_tipo_domanda, t.tipo,
                    (
                        SELECT tr.traduzione
                        FROM ct_traduzioni tr
                        WHERE tr.nome_tabella = \'ct_tipi_domande\'
                          AND tr.nome_campo = \'tipo\'
                          AND tr.lingua = \'en\'
                          AND tr.fk_collegamento = t.id_tipo_domanda
                        LIMIT 1
                    ) AS tipo_en,
                    d.num_gruppo, l.titolo_libro, l.autori, l.casa_editrice
             FROM ct_domande d
             INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
             INNER JOIN ct_tipi_domande t ON t.id_tipo_domanda = d.fk_tipo_domanda
             INNER JOIN ct_libri_testo l ON l.id_libro_testo = d.fk_libro
             WHERE d.fk_argomento = :fk_argomento AND ud.fk_utente = :fk_utente
             ORDER BY d.id_domanda DESC'
        );
        $stmt->execute(['fk_argomento' => $topicId, 'fk_utente' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getImportableQuestionsByTopicForUser(int $topicId, int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.id_domanda, d.domanda, d.fk_tipo_domanda, t.tipo,
                    (
                        SELECT tr.traduzione
                        FROM ct_traduzioni tr
                        WHERE tr.nome_tabella = \'ct_tipi_domande\'
                          AND tr.nome_campo = \'tipo\'
                          AND tr.lingua = \'en\'
                          AND tr.fk_collegamento = t.id_tipo_domanda
                        LIMIT 1
                    ) AS tipo_en,
                    l.titolo_libro, l.autori, l.casa_editrice, creator.username AS autore
             FROM ct_domande d
             INNER JOIN ct_tipi_domande t ON t.id_tipo_domanda = d.fk_tipo_domanda
             INNER JOIN ct_libri_testo l ON l.id_libro_testo = d.fk_libro
             INNER JOIN ct_utenti creator ON creator.id_utente = d.fk_utente
             WHERE d.fk_argomento = :fk_argomento
               AND d.fk_utente <> :fk_utente
               AND d.id_domanda NOT IN (
                   SELECT ud.fk_domanda
                   FROM ct_utente_domande ud
                   WHERE ud.fk_utente = :fk_utente
               )
             ORDER BY d.id_domanda DESC'
        );
        $stmt->execute([
            'fk_argomento' => $topicId,
            'fk_utente' => $userId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findImportableQuestionByIdForUser(int $questionId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.id_domanda, d.fk_argomento
             FROM ct_domande d
             INNER JOIN ct_argomenti a ON a.id_argomento = d.fk_argomento
             INNER JOIN ct_utenti_materie um ON um.fk_materia = a.fk_materia
             WHERE d.id_domanda = :id_domanda
               AND d.fk_utente <> :user_id_owner_check
               AND um.fk_utente = :user_id_scope
               AND d.id_domanda NOT IN (
                   SELECT ud.fk_domanda
                   FROM ct_utente_domande ud
                   WHERE ud.fk_utente = :user_id_not_owned
               )
             LIMIT 1'
        );
        $stmt->execute([
            'id_domanda' => $questionId,
            'user_id_owner_check' => $userId,
            'user_id_scope' => $userId,
            'user_id_not_owned' => $userId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function isCsvQuestionRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }

    private function isCsvQuestionHeaderRow(array $row): bool
    {
        $firstCell = strtolower(trim((string) ($row[0] ?? '')));
        $secondCell = strtolower(trim((string) ($row[1] ?? '')));

        return in_array($firstCell, ['domanda', 'question'], true)
            && in_array($secondCell, ['tipo_domanda', 'tipo domanda', 'type', 'question_type'], true);
    }

    private function parseCsvQuestionRow(array $row): ?array
    {
        $questionText = html_entity_decode(trim((string) ($row[0] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($questionText === '') {
            return null;
        }

        $typeRaw = strtolower(trim((string) ($row[1] ?? '')));
        $typeMap = [
            'risposta aperta' => 1,
            'scelta multipla' => 2,
            'risposta multipla' => 3,
            'esercizio con numeri' => 4,
        ];
        $questionType = $typeMap[$typeRaw] ?? 1;

        $meta = trim((string) ($row[8] ?? '0'));
        $points = (float) trim((string) ($row[9] ?? '1'));
        if ($points <= 0) {
            $points = 1;
        }

        $answers = [];
        $correctIndexes = [];
        if (in_array($questionType, [2, 3], true)) {
            for ($i = 2; $i <= 7; $i++) {
                $answer = html_entity_decode(trim((string) ($row[$i] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($answer !== '') {
                    $answers[] = $answer;
                }
            }

            if (count($answers) < 2) {
                return null;
            }

            if ($questionType === 2) {
                $singleCorrectIndex = (int) trim((string) $meta);
                
                if ($singleCorrectIndex < 1 || $singleCorrectIndex > count($answers)) {
                    $singleCorrectIndex = 1;
                }
                
                $correctIndexes = [$singleCorrectIndex];
            } else {
                $rawIndexes = array_filter(array_map(
                    static fn (string $value): int => (int) trim($value),
                    explode(',', (string) $meta)
                ), static fn (int $value): bool => $value > 0);

                $validIndexes = [];
                foreach ($rawIndexes as $index) {
                    if ($index >= 1 && $index <= count($answers)) {
                        $validIndexes[] = $index;
                    }
                }
                $correctIndexes = array_values(array_unique($validIndexes));
                if ($correctIndexes === []) {
                    return null;
                }
            }
        }

        return [
            'domanda' => $questionText,
            'tipo_domanda' => $questionType,
            'risposte' => $answers,
            'corretta_indexes' => $correctIndexes,
            'num_righe' => $questionType === 1 ? max(1, (int) $meta) : 0,
            'ese_num' => $questionType === 4 ? (string) (html_entity_decode($row[8], ENT_QUOTES | ENT_HTML5, 'UTF-8') ?? '') : '',
            'punti' => $points,
            'argomento' => html_entity_decode(trim((string) ($row[10] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ];
    }

    private function normalizeCsvTopicName(string $topicName): string
    {
        return mb_strtolower(trim($topicName), 'UTF-8');
    }

    private function findBookById(int $bookId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_libro_testo, titolo_libro, autori, casa_editrice, disattivato
             FROM ct_libri_testo
             WHERE id_libro_testo = :id_libro_testo
             LIMIT 1'
        );
        $stmt->execute(['id_libro_testo' => $bookId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function getBooks(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id_libro_testo, titolo_libro, autori, casa_editrice, disattivato
             FROM ct_libri_testo
             ORDER BY disattivato ASC, titolo_libro ASC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getQuestionTypes(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id_tipo_domanda, tipo,
                    (
                        SELECT tr.traduzione
                        FROM ct_traduzioni tr
                        WHERE tr.nome_tabella = \'ct_tipi_domande\'
                          AND tr.nome_campo = \'tipo\'
                          AND tr.lingua = \'en\'
                          AND tr.fk_collegamento = ct_tipi_domande.id_tipo_domanda
                        LIMIT 1
                    ) AS tipo_en
             FROM ct_tipi_domande
             ORDER BY id_tipo_domanda ASC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findQuestionByIdForUser(int $questionId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.*
             FROM ct_domande d
             INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
             WHERE d.id_domanda = :id_domanda AND ud.fk_utente = :fk_utente
             LIMIT 1'
        );
        $stmt->execute(['id_domanda' => $questionId, 'fk_utente' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function findQuestionOwnedByUser(int $questionId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_domanda, fk_utente
             FROM ct_domande
             WHERE id_domanda = :id_domanda AND fk_utente = :fk_utente
             LIMIT 1'
        );
        $stmt->execute(['id_domanda' => $questionId, 'fk_utente' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function getAnswersForQuestion(int $questionId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_risposta, risposta, corretta
             FROM ct_risposte
             WHERE fk_domanda = :fk_domanda
             ORDER BY id_risposta ASC'
        );
        $stmt->execute(['fk_domanda' => $questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function normalizeQuestionPayload(array $input, bool $withType = true): array
    {
        $answers = is_array($input['risposta'] ?? null) ? $input['risposta'] : [];
        $correct = is_array($input['corretta'] ?? null) ? $input['corretta'] : [];
        return [
            'domanda' => trim((string) ($input['domanda'] ?? '')),
            'punti' => (float) ($input['punti'] ?? 0),
            'livello_diff' => max(1, min(5, (int) ($input['livello_diff'] ?? 3))),
            'num_gruppo' => max(0, (int) ($input['num_gruppo'] ?? 0)),
            'fk_libro' => (int) ($input['libro'] ?? 0),
            'tipo_domanda' => $withType ? (int) ($input['tipo_domanda'] ?? 1) : 1,
            'num_righe' => max(0, (int) ($input['num_righe'] ?? 0)),
            'ese_num' => (string) ($input['ese_num'] ?? ''),
            'answers' => array_values($answers),
            'correct' => array_values($correct),
        ];
    }

    private function validateQuestionPayload(array $payload): ?string
    {
        if ($payload['domanda'] === '') {
            return $this->tr('testcreator.service.question.text_required');
        }
        if ($payload['fk_libro'] <= 0) {
            return $this->tr('testcreator.service.question.book_required');
        }
        if ((int) $payload['tipo_domanda'] === 1 && $payload['num_righe'] <= 0) {
            return 'Per le domande aperte indica il numero di righe.';
        }
        if (in_array((int) $payload['tipo_domanda'], [2, 3], true)) {
            $nonEmpty = array_values(array_filter(array_map('trim', $payload['answers']), static fn (string $v): bool => $v !== ''));
            if (count($nonEmpty) < 2) {
                return $this->tr('testcreator.service.question.answers_required');
            }
        }

        return null;
    }

    private function saveAnswers(PDO $pdo, int $questionId, array $payload): void
    {
        if (!in_array((int) $payload['tipo_domanda'], [2, 3], true)) {
            return;
        }

        $insert = $pdo->prepare(
            'INSERT INTO ct_risposte (risposta, corretta, fk_domanda)
             VALUES (:risposta, :corretta, :fk_domanda)'
        );

        foreach ($payload['answers'] as $index => $answer) {
            $answerValue = trim((string) $answer);
            if ($answerValue === '') {
                continue;
            }
            $isCorrect = (isset($payload['correct'][$index]) && (string) $payload['correct'][$index] === 'si') ? 1 : 0;
            $insert->execute([
                'risposta' => htmlentities($answerValue, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'corretta' => $isCorrect,
                'fk_domanda' => $questionId,
            ]);
        }
    }

    private function getQuizzesForUser(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.id_quiz, q.nome_quiz, q.casuale, q.fk_materia, m.nome_materia, q.data_creazione
             FROM ct_quiz q
             INNER JOIN ct_materie m ON m.id_materia = q.fk_materia
             WHERE q.fk_utente = :fk_utente
             ORDER BY q.id_quiz DESC'
        );
        $stmt->execute(['fk_utente' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $countRandom = Database::getConnection()->prepare(
            'SELECT SUM(num_domande) FROM ct_quiz_tipo_domande WHERE fk_quiz = :fk_quiz'
        );
        $countSelected = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM ct_quiz_domande WHERE fk_quiz = :fk_quiz'
        );

        foreach ($rows as &$row) {
            $quizId = (int) ($row['id_quiz'] ?? 0);
            $casuale = (int) ($row['casuale'] ?? 0);
            if ($casuale === 1) {
                $countRandom->execute(['fk_quiz' => $quizId]);
                $row['tot_domande'] = (int) ($countRandom->fetchColumn() ?: 0);
            } else {
                $countSelected->execute(['fk_quiz' => $quizId]);
                $row['tot_domande'] = (int) ($countSelected->fetchColumn() ?: 0);
            }
        }
        unset($row);

        return $rows;
    }

    private function getTopicsGroupedBySubject(array $subjectIds): array
    {
        $subjectIds = array_values(array_filter(array_map('intval', $subjectIds), static fn (int $id): bool => $id > 0));
        if ($subjectIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
        $stmt = Database::getConnection()->prepare(
            sprintf(
                'SELECT id_argomento, nome_argomento, fk_materia
                 FROM ct_argomenti
                 WHERE fk_materia IN (%s)
                 ORDER BY nome_argomento ASC',
                $placeholders
            )
        );
        $stmt->execute($subjectIds);

        $grouped = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $topic) {
            $subjectId = (int) ($topic['fk_materia'] ?? 0);
            if (!isset($grouped[$subjectId])) {
                $grouped[$subjectId] = [];
            }
            $grouped[$subjectId][] = [
                'id_argomento' => (int) ($topic['id_argomento'] ?? 0),
                'nome_argomento' => (string) ($topic['nome_argomento'] ?? ''),
            ];
        }

        return $grouped;
    }

    private function getEvaluationGrids(int $userId, bool $withContent = false): array
    {
        $columns = $withContent ? 'id_griglia, nome_griglia, griglia' : 'id_griglia, nome_griglia';
        $stmt = Database::getConnection()->prepare(
            'SELECT ' . $columns . '
             FROM ct_griglie_valutazione
             WHERE fk_utente = :fk_utente AND attiva = 1
             ORDER BY nome_griglia ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findQuizByIdForUser(int $quizId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_quiz, nome_quiz, fk_materia, nome_materia, mostra_punti_dom, mix_questions, casuale, mix_answer, fk_griglia
             FROM ct_quiz join ct_materie on fk_materia=id_materia
             WHERE id_quiz = :id_quiz AND fk_utente = :fk_utente
             LIMIT 1'
        );
        $stmt->execute(['id_quiz' => $quizId, 'fk_utente' => $userId]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quiz === false ? null : $quiz;
    }

    private function findGridByIdForUser(int $gridId, int $userId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_griglia, nome_griglia, griglia, fk_utente, attiva
             FROM ct_griglie_valutazione
             WHERE id_griglia = :id_griglia
               AND fk_utente = :fk_utente
               AND attiva = 1
             LIMIT 1'
        );
        $stmt->execute([
            'id_griglia' => $gridId,
            'fk_utente' => $userId,
        ]);
        $grid = $stmt->fetch(PDO::FETCH_ASSOC);
        return $grid === false ? null : $grid;
    }

    private function getQuizTopicIds(int $quizId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT fk_argomento
             FROM ct_quiz_argomenti
             WHERE fk_quiz = :fk_quiz'
        );
        $stmt->execute(['fk_quiz' => $quizId]);
        return array_values(array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []));
    }

    private function getQuizTypeRules(int $quizId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT fk_tipo_domande, num_domande
             FROM ct_quiz_tipo_domande
             WHERE fk_quiz = :fk_quiz
             ORDER BY id_quiz_tipo ASC'
        );
        $stmt->execute(['fk_quiz' => $quizId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return array_map(static function (array $row): array {
            return [
                'fk_tipo_domande' => (int) ($row['fk_tipo_domande'] ?? 0),
                'num_domande' => (int) ($row['num_domande'] ?? 0),
            ];
        }, $rows);
    }

    private function getQuizQuestionIds(int $quizId): array
    {
        $stmt = Database::getConnection()->prepare('SELECT fk_domanda FROM ct_quiz_domande WHERE fk_quiz = :fk_quiz');
        $stmt->execute(['fk_quiz' => $quizId]);
        return array_values(array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []));
    }

    private function getQuestionsByTopicsForUser(array $topicIds, int $userId): array
    {
        $topicIds = array_values(array_filter(array_map('intval', $topicIds), static fn (int $id): bool => $id > 0));
        if ($topicIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($topicIds), '?'));
        $sql = sprintf(
            'SELECT d.id_domanda, d.domanda, d.fk_tipo_domanda, t.tipo,
                    (
                        SELECT tr.traduzione
                        FROM ct_traduzioni tr
                        WHERE tr.nome_tabella = \'ct_tipi_domande\'
                          AND tr.nome_campo = \'tipo\'
                          AND tr.lingua = \'en\'
                          AND tr.fk_collegamento = t.id_tipo_domanda
                        LIMIT 1
                    ) AS tipo_en,
                    a.nome_argomento
             FROM ct_domande d
             INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
             INNER JOIN ct_tipi_domande t ON t.id_tipo_domanda = d.fk_tipo_domanda
             INNER JOIN ct_argomenti a ON a.id_argomento = d.fk_argomento
             WHERE ud.fk_utente = ? AND d.fk_argomento IN (%s)
             ORDER BY d.fk_tipo_domanda ASC, d.id_domanda ASC',
            $placeholders
        );
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(array_merge([$userId], $topicIds));
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function extractQuizQuestionIds(array $quiz, int $userId): array
    {
        $quizId = (int) ($quiz['id_quiz'] ?? 0);
        if ((int) ($quiz['casuale'] ?? 1) !== 1) {
            return $this->getQuizQuestionIds($quizId);
        }

        $rules = $this->getQuizTypeRules($quizId);
        $questionIds = [];
        foreach ($rules as $rule) {
            $typeId = (int) ($rule['fk_tipo_domande'] ?? 0);
            $num = max(1, (int) ($rule['num_domande'] ?? 0));
            $questionIds = array_merge($questionIds, $this->extractRandomQuestionIdsByRule($quizId, $userId, $typeId, $num));
        }
        return array_values(array_filter(array_map('intval', $questionIds)));
    }

    private function extractRandomQuestionIdsByRule(int $quizId, int $userId, int $typeId, int $numQuestions): array
    {
        $params = ['quiz_id' => $quizId, 'user_id' => $userId];
        $typeClause = '';
        if ($typeId > 0) {
            $typeClause = 'AND d.fk_tipo_domanda = :type_id';
            $params['type_id'] = $typeId;
        }

        $groupStmt = Database::getConnection()->prepare(
            "SELECT DISTINCT d.num_gruppo
             FROM ct_domande d
             INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
             WHERE d.fk_argomento IN (SELECT fk_argomento FROM ct_quiz_argomenti WHERE fk_quiz = :quiz_id)
               AND ud.fk_utente = :user_id
               AND d.num_gruppo <> 0 $typeClause"
        );
        $groupStmt->execute($params);
        $groups = array_values(array_map('intval', $groupStmt->fetchAll(PDO::FETCH_COLUMN) ?: []));

        $limit = max(0, $numQuestions - count($groups));
        $questionIds = [];
        if ($limit > 0) {
            $regularStmt = Database::getConnection()->prepare(
                "SELECT id_domanda FROM (
                    SELECT d.id_domanda, d.livello_diff
                    FROM ct_domande d
                    INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
                    WHERE d.fk_argomento IN (SELECT fk_argomento FROM ct_quiz_argomenti WHERE fk_quiz = :quiz_id)
                      AND ud.fk_utente = :user_id
                      AND d.num_gruppo = 0 $typeClause
                    ORDER BY RAND()
                    LIMIT $limit
                ) as doms
                ORDER BY livello_diff"
            );
            $regularStmt->execute($params);
            $questionIds = array_values(array_map('intval', $regularStmt->fetchAll(PDO::FETCH_COLUMN) ?: []));
        }

        foreach ($groups as $groupNumber) {
            $groupParams = $params;
            $groupParams['group_number'] = $groupNumber;
            $groupQuestionStmt = Database::getConnection()->prepare(
                "SELECT d.id_domanda
                 FROM ct_domande d
                 INNER JOIN ct_utente_domande ud ON ud.fk_domanda = d.id_domanda
                 WHERE d.fk_argomento IN (SELECT fk_argomento FROM ct_quiz_argomenti WHERE fk_quiz = :quiz_id)
                   AND ud.fk_utente = :user_id
                   AND d.num_gruppo = :group_number $typeClause
                 ORDER BY RAND()
                 LIMIT 1"
            );
            $groupQuestionStmt->execute($groupParams);
            $id = (int) ($groupQuestionStmt->fetchColumn() ?: 0);
            if ($id > 0) {
                $questionIds[] = $id;
            }
        }

        return $questionIds;
    }

    private function loadQuestionsWithAnswers(array $questionIds): array
    {
        if ($questionIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
        $stmt = Database::getConnection()->prepare(
            sprintf('SELECT id_domanda, domanda,num_righe, fk_tipo_domanda, ese_num FROM ct_domande WHERE id_domanda IN (%s)', $placeholders)
        );
        $stmt->execute($questionIds);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $map = [];
        foreach ($questions as $q) {
            $map[(int) $q['id_domanda']] = $q;
        }
        $sorted = [];
        foreach ($questionIds as $id) {
            if (!isset($map[(int) $id])) {
                continue;
            }
            $q = $map[(int) $id];
            $q['answers'] = $this->getAnswersForQuestion((int) $id);
            $sorted[] = $q;
        }
        return $sorted;
    }

    private function buildExerciseTexts(array $questions): array
    {
        $exercises = [];
        foreach ($questions as $question) {
            if ((int) ($question['fk_tipo_domanda'] ?? 0) !== 4) {
                continue;
            }
            $text = (string) ($question['ese_num'] ?? '');
            $count = 0;
            while (strpos($text, '%%') !== false && strpos($text, '??') !== false && $count < 10) {
                $count++;
                $start = strpos($text, '%%');
                $end = strpos($text, '??', $start);
                if ($start === false || $end === false) {
                    break;
                }
                $chunk = substr($text, $start, $end - $start);
                $parts = explode(',', substr($chunk, 2));
                $min = isset($parts[0]) ? (int) $parts[0] : 0;
                $max = isset($parts[1]) ? (int) $parts[1] : $min;
                if ($max < $min) {
                    [$min, $max] = [$max, $min];
                }
                $random = random_int($min, $max);
                $text = str_replace($chunk . '??', (string) $random, $text);
            }
            $exercises[] = $text;
        }
        return $exercises;
    }

    private function getStoredOrGeneratedQuizQuestionIds(array $quiz, int $userId): array
    {
        $sessionQuestionIds = is_array($_SESSION['array_domande'] ?? null) ? $_SESSION['array_domande'] : [];
        $sessionQuestionIds = array_values(array_filter(array_map('intval', $sessionQuestionIds), static fn (int $id): bool => $id > 0));
        if ($sessionQuestionIds !== []) {
            return $sessionQuestionIds;
        }

        $ids = $this->extractQuizQuestionIds($quiz, $userId);
        if ((int) ($quiz['mix_questions'] ?? 0) === 0) {
            shuffle($ids);
        }
        return $ids;
    }

    private function prepareQuestionsForRender(array $questions, array $exerciseTexts, array $options): array
    {
        $isDsa = (($options['dsa'] ?? false) === true);
        $reduce20 = $isDsa && (($options['perc20meno'] ?? false) === true);
        $answers3 = $isDsa && (($options['risp3'] ?? false) === true);

        $rendered = [];
        $exerciseIndex = 0;
        foreach ($questions as $question) {
            $entry = $question;
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 4) {
                $entry['rendered_ese_num'] = (string) ($exerciseTexts[$exerciseIndex] ?? ($question['ese_num'] ?? ''));
                $exerciseIndex++;
            }
            if ($answers3 && in_array((int) ($question['fk_tipo_domanda'] ?? 0), [2, 3], true)) {
                $entry['answers'] = array_slice($entry['answers'] ?? [], 0, 3);
            }
            $rendered[] = $entry;
        }

        if ($reduce20) {
            $toRemove = (int) ceil(count($rendered) * 0.2);
            if ($toRemove > 0) {
                $rendered = array_slice($rendered, 0, max(1, count($rendered) - $toRemove));
            }
        }

        return $rendered;
    }

    private function buildQuizHtml(int $userId, array $quiz, array $questions, bool $isDsa, string $gridHtml = ''): array
    {
        $quizName = htmlspecialchars((string) ($quiz['nome_quiz'] ?? 'Quiz'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $templateName = $this->getUserPrintTemplate($userId) ?? 'Vuoto';
        if (!in_array($templateName, $this->getPrintTemplateDirectories(), true)) {
            $templateName = 'Vuoto';
        }
        $projectRoot = dirname(__DIR__, 2);
        $templateDir = $projectRoot . '/public/assets/template/' . $templateName;
        $headerPath = $templateDir . '/header.html';
        $introPath = $templateDir . '/intro.html';
        $footerPath = $templateDir . '/footer.html';

        $header = is_file($headerPath) ? (string) file_get_contents($headerPath) : '';
        $intro = is_file($introPath) ? (string) file_get_contents($introPath) : '';
        $footer = is_file($footerPath) ? (string) file_get_contents($footerPath) : '';

        $header = $this->normalizePrintHtmlAssets($header, $projectRoot, $templateName);
        $intro = $this->normalizePrintHtmlAssets($intro, $projectRoot, $templateName);
        $footer = $this->normalizePrintHtmlAssets($footer, $projectRoot, $templateName);

        $html = $intro;
        if (stripos($html, '<body') === false) {
            $html = '<html><head><meta charset="utf-8"></head><body>' . $html;
        }

        $quizSubject = htmlspecialchars((string) ($quiz['nome_materia'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $html .= '<div id="titolo_quiz">' . $this->tr('testcreator.service.pdf.assignment_of') . ' ' . $quizSubject . ' - ' . $quizName . '</div>';
        $html .= '<div id="domande">';

        foreach ($questions as $index => $question) {
            $html .= '<div class="titolo_domanda">' . $this->tr('testcreator.service.pdf.question_label') . ' ' . ($index + 1) . '</div>';
            $html .= '<div class="domanda">' . html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES) . '</div>';
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 4) {
                $html .= '<div class="risp_multipla" style="text-align:justify;">' . html_entity_decode((string) ($question['rendered_ese_num'] ?? ''), ENT_QUOTES) . '</div>';
            }
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 1) {
                $html .= '<div class="spazio_risposta">';
                for ($j = 0; $j < (int) ($question['num_righe'] ?? 0); $j++) {
                    $html .= '_______________________________________________________________________________________________________________<br />';
                }
                $html .= '</div>';
            }
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 2) {
                foreach (($question['answers'] ?? []) as $answer) {
                    $html .= '<div class="div_circle"><img src="file://' . $projectRoot . '/public/assets/images/circle.png" class="img_circle"></div>';
                    $html .= '<div class="risp_multipla">';
                    $html .= html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES);
                    $html .= '</div>';
                }
            }
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 3) {
                foreach (($question['answers'] ?? []) as $answer) {
                    $html .= '<div class="div_square"><img src="file://' . $projectRoot . '/public/assets/images/square.png" class="img_square"></div>';
                    $html .= '<div class="risp_multipla">';
                    $html .= html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES);
                    $html .= '</div>';
                }
            }
        }
        $html .= '</div>';

        if (trim($gridHtml) !== '') {
            $html .= '<div id="griglia">' . $gridHtml . '</div>';
        }

        $html .= '</body></html>';
        return [
            'html' => $html,
            'header' => $header,
            'footer' => $footer,
        ];
    }

    private function normalizePrintHtmlAssets(string $html, string $projectRoot, string $templateName): string
    {
        if ($html === '') {
            return '';
        }

        $stampaCss = 'file://' . $projectRoot . '/public/css/stampa.css';
        $html = str_replace(['href="../css/stampa.css"', 'href="/css/stampa.css"'], 'href="' . $stampaCss . '"', $html);

        $templateAssetUrl = '/assets/template/' . $templateName . '/';
        $templateAssetLegacy = './template/' . $templateName . '/';
        $templateAssetFile = 'file://' . $projectRoot . '/public/assets/template/' . $templateName . '/';
        $html = str_replace([$templateAssetUrl, $templateAssetLegacy], $templateAssetFile, $html);

        return $html;
    }

    private function buildCoCorrectionPdf(int $quizId, array $quiz, array $options): array
    {
        $quizName = htmlspecialchars((string) ($quiz['nome_quiz'] ?? 'Quiz'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $qta = max(1, (int) ($options['qta_cocorrezione'] ?? 1));
        $rows = '';
        for ($i = 0; $i < $qta; $i++) {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));
            $rows .= '<tr><td style="border:1px solid #333;padding:6px;">' . ($i + 1) . '</td><td style="border:1px solid #333;padding:6px;">' . $code . '</td></tr>';
        }

        $html = '<html><head><meta charset="utf-8"></head><body>';
        $html .= '<h2>Compito in co-correzione - ' . $quizName . '</h2>';
        $html .= '<table style="border-collapse:collapse;width:100%"><thead><tr><th style="border:1px solid #333;padding:6px;">#</th><th style="border:1px solid #333;padding:6px;">Codice</th></tr></thead><tbody>' . $rows . '</tbody></table>';
        $html .= '</body></html>';

        try {
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($html);
            return ['success' => true, 'pdf' => $mpdf->Output('', 'S')];
        } catch (Throwable $exception) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.pdf.correction_error_prefix') . $exception->getMessage()];
        }
    }

    private function persistGrid(array $input, bool $isUpdate): array
    {
        $permissionService = new PermissionService();
        if ($permissionService->checkTeacherAreaAccess() !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.unauthorized'), 'redirect' => '/testcreator/griglie'];
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.invalid_session'), 'redirect' => '/'];
        }

        $payload = $this->normalizeGridPayload($input);
        $validationError = $this->validateGridPayload($payload);
        $gridId = $isUpdate ? (int) ($input['id_griglia'] ?? 0) : 0;

        if ($isUpdate && $gridId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.grid.invalid'), 'redirect' => '/testcreator/griglie'];
        }

        if ($validationError !== null) {
            $redirect = $isUpdate ? '/testcreator/griglie/' . $gridId . '/modifica' : '/testcreator/griglie/nuova';
            return ['success' => false, 'message' => $validationError, 'redirect' => $redirect];
        }

        if ($isUpdate) {
            $grid = $this->findGridByIdForUser($gridId, $userId);
            if ($grid === null) {
                return ['success' => false, 'message' => $this->tr('testcreator.service.grid.not_found_or_inaccessible'), 'redirect' => '/testcreator/griglie'];
            }
        }

        try {
            $pdo = Database::getConnection();
            if ($isUpdate) {
                $stmt = $pdo->prepare(
                    'UPDATE ct_griglie_valutazione
                     SET nome_griglia = :nome_griglia, griglia = :griglia
                     WHERE id_griglia = :id_griglia AND fk_utente = :fk_utente'
                );
                $stmt->execute([
                    'nome_griglia' => $payload['nome_griglia'],
                    'griglia' => htmlentities($payload['griglia'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                    'id_griglia' => $gridId,
                    'fk_utente' => $userId,
                ]);
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO ct_griglie_valutazione (nome_griglia, griglia, fk_utente, attiva)
                     VALUES (:nome_griglia, :griglia, :fk_utente, 1)'
                );
                $stmt->execute([
                    'nome_griglia' => $payload['nome_griglia'],
                    'griglia' => htmlentities($payload['griglia'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                    'fk_utente' => $userId,
                ]);
            }
        } catch (Throwable $exception) {
            $redirect = $isUpdate ? '/testcreator/griglie/' . $gridId . '/modifica' : '/testcreator/griglie/nuova';
            return ['success' => false, 'message' => $this->tr('testcreator.service.grid.save_error_prefix') . $exception->getMessage(), 'redirect' => $redirect];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.grid.saved'), 'redirect' => '/testcreator/griglie'];
    }

    public function getTeacherEmailsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'userDisplayName' => '',
            'isAdmin' => false,
            'emails' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['isAdmin'] = $this->isAdmin($userId);
        if ($data['isAdmin']) {
            $data['emails'] = $this->getAllowedTeacherEmails();
        }

        return $data;
    }

    public function saveTeacherEmail(string $email): array
    {
        $adminCheck = $this->guardAdminAction();
        if ($adminCheck !== null) {
            return $adminCheck;
        }

        $normalizedEmail = mb_strtolower(trim($email));
        if ($normalizedEmail === '') {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.required')];
        }
        if (!filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.invalid')];
        }

        $exists = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_mail_abilitate
             WHERE LOWER(mail) = LOWER(:mail)'
        );
        $exists->execute(['mail' => $normalizedEmail]);
        if ((int) $exists->fetchColumn() > 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.already_whitelisted')];
        }

        $stmt = Database::getConnection()->prepare(
            'INSERT INTO ct_mail_abilitate (mail)
             VALUES (:mail)'
        );
        $stmt->execute(['mail' => $normalizedEmail]);

        return ['success' => true, 'message' => $this->tr('testcreator.service.mail.added')];
    }

    public function importTeacherEmailsCsv(array $files): array
    {
        $adminCheck = $this->guardAdminAction();
        if ($adminCheck !== null) {
            return $adminCheck;
        }

        $file = $files['csv_file'] ?? null;
        if (!is_array($file) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.csv_required')];
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.csv_invalid_upload')];
        }

        $emails = $this->extractEmailsFromCsv($tmpName);
        if ($emails === []) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.csv_no_valid')];
        }

        $emails = array_values(array_unique($emails));
        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->exec('DELETE FROM ct_mail_abilitate');
            $insert = $pdo->prepare('INSERT INTO ct_mail_abilitate (mail) VALUES (:mail)');
            foreach ($emails as $email) {
                $insert->execute(['mail' => $email]);
            }
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.csv_import_error')];
        }

        return ['success' => true, 'message' => sprintf($this->tr('testcreator.service.mail.csv_imported'), count($emails))];
    }

    public function deleteTeacherEmail(int $emailId): array
    {
        $adminCheck = $this->guardAdminAction();
        if ($adminCheck !== null) {
            return $adminCheck;
        }

        if ($emailId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.invalid')];
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_mail_abilitate
             WHERE id_mail_abilitata = :id_mail_abilitata'
        );
        $stmt->execute(['id_mail_abilitata' => $emailId]);

        if ($stmt->rowCount() < 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.mail.not_found_or_removed')];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.mail.deleted')];
    }

    public function getAdministratorsPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkTeacherAreaAccess();

        $data = [
            'permissionStatus' => $permissionStatus,
            'userDisplayName' => '',
            'isAdmin' => false,
            'teachers' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $userId = $permissionService->getCurrentUserId();
        if ($userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_LOGGED;
            return $data;
        }

        $data['userDisplayName'] = $this->getUserDisplayName($userId);
        $data['isAdmin'] = $this->isAdmin($userId);
        if ($data['isAdmin']) {
            $data['teachers'] = $this->getTeachersWithAdminState();
        }

        return $data;
    }

    public function promoteAdministrator(int $userId): array
    {
        $adminCheck = $this->guardAdminAction();
        if ($adminCheck !== null) {
            return $adminCheck;
        }

        if ($userId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.invalid')];
        }

        $teacher = $this->findTeacherById($userId);
        if ($teacher === null) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.not_found')];
        }

        if (((int) ($teacher['is_admin'] ?? 0)) === 1) {
            return ['success' => true, 'message' => $this->tr('testcreator.service.teacher.already_admin')];
        }

        $adminRoleId = $this->getAdminRoleId();
        if ($adminRoleId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.admin_role_missing')];
        }

        $stmt = Database::getConnection()->prepare(
            'INSERT INTO ct_utenti_tipi (fk_utente, fk_tipo_utente)
             VALUES (:fk_utente, :fk_tipo_utente)'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'fk_tipo_utente' => $adminRoleId,
        ]);

        return ['success' => true, 'message' => $this->tr('testcreator.service.teacher.admin_assigned')];
    }

    public function removeAdministrator(int $userId): array
    {
        $adminCheck = $this->guardAdminAction();
        if ($adminCheck !== null) {
            return $adminCheck;
        }

        if ($userId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.invalid')];
        }

        if ($userId === 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.primary_admin_locked')];
        }

        $adminRoleId = $this->getAdminRoleId();
        if ($adminRoleId <= 0) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.admin_role_missing')];
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_utenti_tipi
             WHERE fk_utente = :fk_utente
               AND fk_tipo_utente = :fk_tipo_utente'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'fk_tipo_utente' => $adminRoleId,
        ]);

        if ($stmt->rowCount() < 1) {
            return ['success' => false, 'message' => $this->tr('testcreator.service.teacher.not_admin')];
        }

        return ['success' => true, 'message' => $this->tr('testcreator.service.teacher.admin_removed')];
    }

    private function getPrintTemplateDirectories(): array
    {
        $path = dirname(__DIR__, 2) . '/public/assets/template';
        if (!is_dir($path)) {
            return [];
        }

        $entries = scandir($path);
        if ($entries === false) {
            return [];
        }

        $templates = [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($fullPath)) {
                $templates[] = $entry;
            }
        }

        sort($templates, SORT_NATURAL | SORT_FLAG_CASE);

        return $templates;
    }

    private function getUserPrintTemplate(int $userId): ?string
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT template_stampa
             FROM ct_utenti
             WHERE id_utente = :id_utente
             LIMIT 1'
        );
        $stmt->execute(['id_utente' => $userId]);

        $value = $stmt->fetchColumn();
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return trim($value);
    }

    private function copyDirectory(string $source, string $destination): bool
    {
        if (!is_dir($source)) {
            return false;
        }

        if (!is_dir($destination) && !mkdir($destination, 0775, true) && !is_dir($destination)) {
            return false;
        }

        $items = scandir($source);
        if ($items === false) {
            return false;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $sourcePath = $source . DIRECTORY_SEPARATOR . $item;
            $destinationPath = $destination . DIRECTORY_SEPARATOR . $item;

            if (is_dir($sourcePath)) {
                if (!$this->copyDirectory($sourcePath, $destinationPath)) {
                    return false;
                }
                continue;
            }

            if (!copy($sourcePath, $destinationPath)) {
                return false;
            }
        }

        return true;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        if (!is_dir($dir)) {
            @unlink($dir);
            return;
        }

        $items = scandir($dir);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

    private function normalizeGridPayload(array $input): array
    {
        return [
            'nome_griglia' => trim((string) ($input['nome_griglia'] ?? '')),
            'griglia' => trim((string) ($input['griglia'] ?? '')),
        ];
    }

    private function validateGridPayload(array $payload): ?string
    {
        if ($payload['nome_griglia'] === '') {
            return $this->tr('testcreator.service.grid.name_required');
        }

        if (mb_strlen($payload['nome_griglia']) > 40) {
            return $this->tr('testcreator.service.grid.name_too_long');
        }

        if ($payload['griglia'] === '') {
            return $this->tr('testcreator.service.grid.content_required');
        }

        return null;
    }

    private function decodeStoredHtml(string $value): string
    {
        return html_entity_decode(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function normalizeQuizPayload(array $input): array
    {
        $topicIds = array_values(array_filter(array_map('intval', is_array($input['argomenti'] ?? null) ? $input['argomenti'] : []), static fn (int $id): bool => $id > 0));
        $typeIds = is_array($input['tipo_domande'] ?? null) ? $input['tipo_domande'] : [];
        $questionNumbers = is_array($input['num_domande'] ?? null) ? $input['num_domande'] : [];
        $typeRules = [];

        $length = max(count($typeIds), count($questionNumbers));
        for ($i = 0; $i < $length; $i++) {
            $typeRules[] = [
                'fk_tipo_domande' => max(0, (int) ($typeIds[$i] ?? 0)),
                'num_domande' => max(1, (int) ($questionNumbers[$i] ?? 0)),
            ];
        }

        return [
            'nome_quiz' => trim((string) ($input['nome_quiz'] ?? '')),
            'fk_materia' => (int) ($input['materia'] ?? 0),
            'argomenti' => array_values(array_unique($topicIds)),
            'mostra_punti_dom' => ((int) ($input['mostrapunti'] ?? 1) === 2) ? 2 : 1,
            'mix_questions' => ((int) ($input['mix_questions'] ?? 0) === 1) ? 1 : 0,
            'casuale' => ((string) ($input['acaso'] ?? 'si') === 'no' || (string) ($input['acaso'] ?? '1') === '0') ? 0 : 1,
            'mix_answer' => ((string) ($input['mix_answer'] ?? 'si') === 'no' || (string) ($input['mix_answer'] ?? '1') === '0') ? 0 : 1,
            'fk_griglia' => max(0, (int) ($input['griglia'] ?? 0)),
            'type_rules' => $typeRules,
        ];
    }

    private function validateQuizPayload(array &$payload, int $userId): ?string
    {
        if ($payload['nome_quiz'] === '') {
            return $this->tr('testcreator.service.quiz.name_required');
        }

        if (mb_strlen($payload['nome_quiz']) > 255) {
            return $this->tr('testcreator.service.quiz.name_too_long');
        }

        $subjects = $this->getAssignedSubjects($userId);
        $allowedSubjectIds = array_map(static fn (array $subject): int => (int) ($subject['id_materia'] ?? 0), $subjects);
        if (!in_array($payload['fk_materia'], $allowedSubjectIds, true)) {
            return $this->tr('testcreator.service.quiz.subject_required');
        }

        if ($payload['argomenti'] === []) {
            return $this->tr('testcreator.service.quiz.topic_required');
        }

        $topicStmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_argomenti
             WHERE fk_materia = :fk_materia
               AND id_argomento = :id_argomento'
        );
        foreach ($payload['argomenti'] as $topicId) {
            $topicStmt->execute([
                'fk_materia' => $payload['fk_materia'],
                'id_argomento' => $topicId,
            ]);
            if ((int) $topicStmt->fetchColumn() < 1) {
                return $this->tr('testcreator.service.quiz.invalid_topics');
            }
        }

        if ($payload['casuale'] === 1) {
            if ($payload['type_rules'] === []) {
                return $this->tr('testcreator.service.quiz.random_rule_required');
            }

            $normalizedRules = [];
            foreach ($payload['type_rules'] as $rule) {
                if (($rule['num_domande'] ?? 0) <= 0) {
                    continue;
                }
                $normalizedRules[] = [
                    'fk_tipo_domande' => max(0, (int) ($rule['fk_tipo_domande'] ?? 0)),
                    'num_domande' => max(1, (int) ($rule['num_domande'] ?? 1)),
                ];
            }

            if ($normalizedRules === []) {
                return $this->tr('testcreator.service.quiz.question_count_required');
            }
            $payload['type_rules'] = $normalizedRules;
        }

        return null;
    }

    private function findOrCreateSubjectFromImport(PDO $pdo, string $subjectName, string $subjectUuid): int
    {
        if ($subjectUuid !== '') {
            $stmt = $pdo->prepare('SELECT id_materia FROM ct_materie WHERE uuid = :uuid LIMIT 1');
            $stmt->execute(['uuid' => $subjectUuid]);
            $id = (int) ($stmt->fetchColumn() ?: 0);
            if ($id > 0) {
                return $id;
            }
        }

        $stmt = $pdo->prepare('SELECT id_materia FROM ct_materie WHERE nome_materia = :nome_materia LIMIT 1');
        $stmt->execute(['nome_materia' => $subjectName]);
        $id = (int) ($stmt->fetchColumn() ?: 0);
        if ($id > 0) {
            if ($subjectUuid !== '') {
                $pdo->prepare('UPDATE ct_materie SET uuid = :uuid WHERE id_materia = :id_materia AND (uuid IS NULL OR uuid = "")')
                    ->execute(['uuid' => $subjectUuid, 'id_materia' => $id]);
            }
            return $id;
        }

        $pdo->prepare('INSERT INTO ct_materie (uuid, nome_materia) VALUES (:uuid, :nome_materia)')
            ->execute([
                'uuid' => $subjectUuid !== '' ? $subjectUuid : $this->generateUuidV4(),
                'nome_materia' => $subjectName,
            ]);

        return (int) $pdo->lastInsertId();
    }

    private function findOrCreateTopicFromImport(PDO $pdo, int $subjectId, string $topicName, string $topicUuid): int
    {
        if ($topicUuid !== '') {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE uuid = :uuid LIMIT 1');
            $stmt->execute(['uuid' => $topicUuid]);
            $id = (int) ($stmt->fetchColumn() ?: 0);
            if ($id > 0) {
                return $id;
            }
        }

        $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE nome_argomento = :nome_argomento LIMIT 1');
        $stmt->execute(['nome_argomento' => $topicName]);
        $id = (int) ($stmt->fetchColumn() ?: 0);
        if ($id > 0) {
            if ($topicUuid !== '') {
                $pdo->prepare('UPDATE ct_argomenti SET uuid = :uuid WHERE id_argomento = :id_argomento AND (uuid IS NULL OR uuid = "")')
                    ->execute(['uuid' => $topicUuid, 'id_argomento' => $id]);
            }
            return $id;
        }

        $pdo->prepare('INSERT INTO ct_argomenti (uuid, nome_argomento, fk_materia) VALUES (:uuid, :nome_argomento, :fk_materia)')
            ->execute([
                'uuid' => $topicUuid !== '' ? $topicUuid : $this->generateUuidV4(),
                'nome_argomento' => $topicName,
                'fk_materia' => $subjectId,
            ]);

        return (int) $pdo->lastInsertId();
    }

    private function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
