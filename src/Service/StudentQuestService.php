<?php

namespace App\Service;

use PDO;

class StudentQuestService
{
    private TranslationService $translator;
    private QuestRewardService $questRewardService;

    public function __construct()
    {
        $this->translator = new TranslationService();
        $this->questRewardService = new QuestRewardService();
    }

    public function getQuestIndexPageData(): array
    {
        $data = $this->baseProtectedPageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $data['quests'] = $this->getQuestsForClass($classId);

        return $data;
    }

    public function getQuestMapPageData(int $questId): array
    {
        $data = $this->baseProtectedPageData();
        $data['quest'] = null;
        $data['chapters'] = [];

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $studentId = (int) ($data['student']['id_studente'] ?? 0);

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $data;
        }

        $data['quest'] = $quest;
        $data['chapters'] = $this->getChapterFlagsForStudent($questId, $studentId, (int) ($quest['blocca_ese'] ?? 1));

        return $data;
    }

    public function getProblemDeliveriesPageData(int $questId): array
    {
        $data = $this->getQuestMapPageData($questId);
        $data['problemDeliveries'] = [];

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK || ($data['quest'] ?? null) === null) {
            return $data;
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $data['problemDeliveries'] = $this->getProblemDeliveriesForQuest($classId, $studentId, $questId);

        return $data;
    }

    public function getChapterDetailPageData(int $questId, int $chapterId): array
    {
        $data = $this->baseProtectedPageData();
        $data['quest'] = null;
        $data['chapter'] = null;
        $data['exercises'] = [];

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $studentLevel = (int) ($data['student']['livello'] ?? 1);

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $data;
        }

        $chapter = $this->findChapterInQuest($questId, $chapterId);
        if ($chapter === null) {
            return $data;
        }

        $data['quest'] = $quest;
        $data['chapter'] = $chapter;
        $data['exercises'] = $this->getChapterExercisesForStudent(
            $classId,
            $questId,
            $chapterId,
            $studentId,
            $studentLevel,
            (int) ($quest['blocca_ese'] ?? 1)
        );

        return $data;
    }

    private function baseProtectedPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
            'quests' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();
        if ($classId === null || $userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $pdo = Database::getConnection();
        $classStmt = $pdo->prepare(
            'SELECT c.id_classe, c.nome_classe, c.colore, c.icona, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe AND c.eliminata = 0
             LIMIT 1'
        );
        $classStmt->execute(['id_classe' => $classId]);
        $classroom = $classStmt->fetch(PDO::FETCH_ASSOC) ?: null;

        $studentStmt = $pdo->prepare(
            'SELECT s.*
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.fk_utente = :id_utente AND sc.fk_classe = :id_classe
             LIMIT 1'
        );
        $studentStmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($classroom === null || $student === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $data['classroom'] = $classroom;
        $data['student'] = $student;

        return $data;
    }

    private function getQuestsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.*
             FROM ct_classi_quest cq
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             WHERE cq.fk_classe = :id_classe
             ORDER BY q.nome_quest ASC'
        );
        $stmt->execute(['id_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findQuestById(int $classId, int $questId): ?array
    {
        if ($questId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT q.*
             FROM ct_classi_quest cq
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             WHERE cq.fk_classe = :id_classe
               AND q.id_quest = :id_quest
             LIMIT 1'
        );
        $stmt->execute([
            'id_classe' => $classId,
            'id_quest' => $questId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getChapterFlagsForStudent(int $questId, int $studentId, int $lockMode): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_capitolo, c.coord_x, c.coord_y, cq.progressivo
             FROM ct_capitoli c
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE cq.fk_quest = :id_quest
             ORDER BY cq.progressivo ASC'
        );
        $stmt->execute(['id_quest' => $questId]);
        $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $flags = [];
        foreach ($chapters as $chapter) {
            $chapterId = (int) ($chapter['id_capitolo'] ?? 0);
            $progressive = (int) ($chapter['progressivo'] ?? 0);
            if ($chapterId <= 0 || $progressive <= 0) {
                continue;
            }

            $activeExercises = $this->countActiveExercisesInChapter($questId, $chapterId);
            if ($activeExercises <= 0) {
                continue;
            }

            $completedExercises = $this->countCompletedExercisesInChapter($questId, $chapterId, $studentId);
            $missingExercises = max(0, $activeExercises - $completedExercises);

            $isLocked = false;
            if ($lockMode === 1 && $progressive > 1) {
                $activePreviousExercises = $this->countActiveExercisesBeforeProgressive($questId, $progressive);
                $completedPreviousExercises = $this->countCompletedExercisesBeforeProgressive($questId, $progressive, $studentId);
                $isLocked = $activePreviousExercises > $completedPreviousExercises;
            }

            $flagImage = '/assets/images/bandiera_verde.png';
            $link = '/studenti/quest/' . $questId . '/capitoli/' . $chapterId;

            if ($isLocked) {
                $flagImage = '/assets/images/bandiera_grigia.png';
                $link = '#';
            } elseif ($missingExercises > 0) {
                $flagImage = '/assets/images/bandiera_rossa.png';
            }

            $flags[] = [
                'id' => $chapterId,
                'x' => (int) ($chapter['coord_x'] ?? 0),
                'y' => (int) ($chapter['coord_y'] ?? 0),
                'progressive' => $progressive,
                'flagImage' => $flagImage,
                'link' => $link,
            ];
        }

        return $flags;
    }

    private function findChapterInQuest(int $questId, int $chapterId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.*, cq.progressivo
             FROM ct_capitoli c
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE cq.fk_quest = :id_quest
               AND c.id_capitolo = :id_capitolo
             LIMIT 1'
        );
        $stmt->execute([
            'id_quest' => $questId,
            'id_capitolo' => $chapterId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getChapterExercisesForStudent(int $classId, int $questId, int $chapterId, int $studentId, int $studentLevel, int $lockMode): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT e.id_esercizio,
                    e.nome_capitolo,
                    e.punti_esperienza,
                    e.livello_diff,
                    e.tipo_esercizio,
                    eq.progressivo,
                    a.nome_argomento,
                    t.tipo,
                    (select tr.traduzione from ct_traduzioni tr where tr.nome_tabella = \'ct_tipi_esercizio\' and tr.nome_campo = \'tipo\' and tr.lingua = \'en\' and tr.fk_collegamento = t.id_tipo_esercizio) as tipo_en,
                    cs.id_consegna,
                    cs.valutato
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_esercizi e ON e.id_esercizio = eq.fk_esercizio
             INNER JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
             INNER JOIN ct_tipi_esercizio t ON t.id_tipo_esercizio = e.tipo_esercizio
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = e.id_esercizio
             LEFT JOIN ct_consegne_studenti cs ON cs.fk_esercizio = e.id_esercizio AND cs.fk_studente = :id_studente
             WHERE cq.fk_quest = :id_quest
               AND cq.fk_capitolo = :id_capitolo
               AND cea.fk_classe = :id_classe
               AND cea.attivo = 1
             ORDER BY eq.progressivo ASC'
        );
        $stmt->execute([
            'id_studente' => $studentId,
            'id_quest' => $questId,
            'id_capitolo' => $chapterId,
            'id_classe' => $classId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($rows as $index => $row) {
            $progressive = (int) ($row['progressivo'] ?? 0);
            $isLocked = false;

            if ($lockMode === 1 && $progressive > 1) {
                $isLocked = !$this->hasCompletedPreviousExercise($studentId, $chapterId, $progressive);
            }

            $isEvaluated = (int) ($row['valutato'] ?? 0) === 1;
            $hasDelivery = !empty($row['id_consegna']);

            $buttonText = $this->translator->translate('student.quest.exercise.new_exercise');
            $buttonClass = 'quest-active';
            if ($isLocked) {
                $buttonText = $this->translator->translate('student.quest.exercise.disabled');
                $buttonClass = 'quest-locked';
            } elseif ($isEvaluated) {
                $buttonText = $this->translator->translate('student.quest.exercise.evaluation');
                $buttonClass = 'quest-evaluated';
            } elseif ($hasDelivery) {
                $buttonText = $this->translator->translate('student.quest.exercise.update_delivery');
                $buttonClass = 'quest-submitted';
            }

            $xpBase = max(0, (int) ($row['punti_esperienza'] ?? 0));
            $levelDiff = max(1, (int) ($row['livello_diff'] ?? 1));
            $xpScaled = (int) max(1, round($xpBase * ($studentLevel / $levelDiff)));

            $rows[$index]['is_locked'] = $isLocked;
            $rows[$index]['button_text'] = $buttonText;
            $rows[$index]['button_class'] = $buttonClass;
            $rows[$index]['xp_display'] = $xpScaled;
            $rows[$index]['detail_url'] = $isLocked
                ? '#'
                : '/studenti/quest/' . $questId . '/capitoli/' . $chapterId . '/esercizi/' . (int) ($row['id_esercizio'] ?? 0);
        }

        return $rows;
    }

    private function getProblemDeliveriesForQuest(int $classId, int $studentId, int $questId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT cs.id_consegna,
                    COALESCE(cs.descrizione_problema, "") AS descrizione_problema,
                    c.id_capitolo,
                    c.nome_capitolo AS nome_capitolo,
                    e.id_esercizio,
                    e.nome_capitolo AS nome_esercizio
             FROM ct_consegne_studenti cs
             INNER JOIN ct_esercizi e ON e.id_esercizio = cs.fk_esercizio
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
             INNER JOIN ct_capitoli c ON c.id_capitolo = eq.fk_capitolo
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             INNER JOIN ct_classi_quest clq ON clq.fk_quest = cq.fk_quest
             WHERE clq.fk_classe = :fk_classe
               AND cs.fk_studente = :fk_studente
               AND cq.fk_quest = :fk_quest
               AND cs.problema = 1
             ORDER BY c.nome_capitolo ASC, e.nome_capitolo ASC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
            'fk_quest' => $questId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getExerciseDetailPageData(int $questId, int $chapterId, int $exerciseId, bool $allowQuestionPreparation = true): array
    {
        $data = $this->baseProtectedPageData();
        $data['quest'] = null;
        $data['chapter'] = null;
        $data['exercise'] = null;
        $data['exerciseState'] = 'new';
        $data['delivery'] = null;
        $data['materialiSupporto'] = [];
        $data['quizQuestions'] = [];
        $data['accessAllowed'] = false;

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $studentLevel = (int) ($data['student']['livello'] ?? 1);

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $data;
        }

        $chapter = $this->findChapterInQuest($questId, $chapterId);
        if ($chapter === null) {
            return $data;
        }

        $exercise = $this->findExerciseInChapterForStudent($classId, $chapterId, $exerciseId);
        if ($exercise === null) {
            return $data;
        }

        $accessAllowed = $this->canAccessExercise((int) ($quest['blocca_ese'] ?? 1), $studentId, $chapterId, $exerciseId);
        $data['accessAllowed'] = $accessAllowed;
        $data['quest'] = $quest;
        $data['chapter'] = $chapter;
        $data['exercise'] = $exercise;

        $ricompense = $this->questRewardService->calculateExerciseRewards($studentLevel, (int) ($exercise['livello_diff'] ?? 1), (int) ($exercise['punti_esperienza'] ?? 0), (int) ($exercise['monete_guadagnate'] ?? 0));
        $data['exercise']['xp_display'] = $ricompense['xp'];
        $data['exercise']['money_display'] = $ricompense['monete'];
        $data['materialiSupporto'] = $this->loadExerciseMaterials($exerciseId, (int) ($exercise['fk_materiale'] ?? 0));

        $delivery = $this->findStudentDelivery($exerciseId, $studentId);
        //print_r($delivery);
        //if($delivery==null) echo "nulla";
        $data['delivery'] = $delivery;
        if ($delivery !== null) {
            $data['exerciseState'] = ((int) ($delivery['valutato'] ?? 0) === 1) ? 'evaluated' : 'submitted';
        }

        //echo "Stato consegna: ".$data['exerciseState'];

        if (in_array((int) ($exercise['tipo_esercizio'] ?? 0), [2, 4], true) && $accessAllowed) {
            $quizType = (int) ($exercise['tipo_esercizio'] ?? 0) === 2 ? 'crocette' : 'misto';
            if (
                $allowQuestionPreparation
                && $data['exerciseState'] === 'new'
                && !$this->hasAssignedQuizQuestions($exerciseId, $studentId)
            ) {
                $this->prepareRandomQuestionsForStudent($exerciseId, $studentId, (int) ($exercise['fk_argomento'] ?? 0), max(1, (int) ($exercise['num_domande'] ?? 1)), $quizType);
            }
            $data['quizQuestions'] = $this->loadQuizQuestionsWithAnswers($exerciseId, $studentId, $delivery === null ? 0 : (int) $delivery['id_consegna']);
        }

        return $data;
    }

    public function submitExercise(int $questId, int $chapterId, int $exerciseId, array $input, array $files): array
    {
        $data = $this->getExerciseDetailPageData($questId, $chapterId, $exerciseId, false);
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => 'student.quest.submit.permission_denied'];
        }
        if (($data['accessAllowed'] ?? false) !== true || ($data['exercise'] ?? null) === null) {
            return ['success' => false, 'message' => 'student.quest.submit.exercise_not_accessible'];
        }

        $studentId = (int) ($data['student']['id_studente'] ?? 0);
        $classId = (int) ($data['classroom']['id_classe'] ?? 0);
        $exercise = $data['exercise'];
        $delivery = $data['delivery'];
        if ($delivery !== null && (int) ($delivery['valutato'] ?? 0) === 1) {
            return ['success' => false, 'message' => 'student.quest.submit.already_evaluated'];
        }

        $pdo = Database::getConnection();
        $isNewDelivery = false;
        $deliveryId = $delivery === null ? 0 : (int) ($delivery['id_consegna'] ?? 0);

        try {
            $pdo->beginTransaction();
            if ($deliveryId <= 0) {
                $stmt = $pdo->prepare('INSERT INTO ct_consegne_studenti (fk_studente, fk_esercizio, valutazione, valutato, data_consegna) VALUES (:fk_studente, :fk_esercizio, 0, 0, NOW())');
                $stmt->execute(['fk_studente' => $studentId, 'fk_esercizio' => $exerciseId]);
                $deliveryId = (int) $pdo->lastInsertId();
                $isNewDelivery = true;
            }

            $exerciseType = (string) ($exercise['tipo'] ?? '');
            if ($exerciseType === 'Domanda aperta') {
                $this->saveOpenAnswer($studentId, $exerciseId, $deliveryId, (string) ($input['testo_risposta'] ?? ''));
            } elseif ($exerciseType === 'Quiz a risposta multipla' || $exerciseType === 'Quiz con risposte multiple e domande aperte') {
                $this->saveQuizAnswers($studentId, $exerciseId, $deliveryId, $input);
            } elseif ($exerciseType === 'Esercizio da consegnare') {
                $relativePath = $this->saveUploadedExerciseFiles($studentId, $deliveryId, $files['file'] ?? []);
                if ($relativePath !== null) {
                    $pdo->prepare('UPDATE ct_consegne_studenti SET file_consegnato = :file_consegnato WHERE id_consegna = :id_consegna')
                        ->execute(['file_consegnato' => $relativePath, 'id_consegna' => $deliveryId]);
                }
                $this->saveOpenAnswer($studentId, $exerciseId, $deliveryId, ' ');
            }

            (new PluginEventBus())->dispatch(PluginEventBus::EVENT_DELIVERY_SUBMITTED, [
                'class_id' => $classId,
                'student_id' => $studentId,
                'delivery_id' => $deliveryId,
                'quest_id' => $questId,
                'chapter_id' => $chapterId,
                'exercise_id' => $exerciseId,
                'exercise_type' => $exerciseType,
                'is_new_delivery' => $isNewDelivery,
            ]);

            $forziereVinto = null;
            if ($isNewDelivery) {
                $forziereVinto = $this->registerNewDeliveryRewards($studentId, $classId);
            }

            $teacherAlert = $this->insertTeacherAlertForDelivery($classId, $studentId, $questId, $chapterId, $exerciseId);
            $pdo->commit();
            $this->sendTeacherDeliveryEmails($classId, $teacherAlert['text'], $teacherAlert['link']);

            return [
                'success' => true,
                'message' => 'student.quest.submit.success',
                'forziere_vinto' => $forziereVinto,
            ];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'message' => 'Errore nel salvare la consegna: ' . $e->getMessage()];
        }
    }

    public function deleteDeliveredFile(int $questId, int $chapterId, int $exerciseId, string $fileName): array
    {
        $data = $this->getExerciseDetailPageData($questId, $chapterId, $exerciseId);
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return ['success' => false, 'message' => 'Permessi insufficienti.'];
        }
        if (($data['accessAllowed'] ?? false) !== true || ($data['exercise'] ?? null) === null) {
            return ['success' => false, 'message' => 'Esercizio non accessibile.'];
        }

        $delivery = $data['delivery'] ?? null;
        if ($delivery === null) {
            return ['success' => false, 'message' => 'Nessuna consegna trovata.'];
        }
        if ((int) ($delivery['valutato'] ?? 0) === 1) {
            return ['success' => false, 'message' => 'Questa consegna è già stata valutata.'];
        }

        $safeFileName = basename(trim($fileName));
        if ($safeFileName === '' || $safeFileName === '.' || $safeFileName === '..') {
            return ['success' => false, 'message' => 'Nome file non valido.'];
        }

        $relativeFolder = (string) ($delivery['file_consegnato'] ?? '');
        if ($relativeFolder === '') {
            return ['success' => false, 'message' => 'Cartella consegna non trovata.'];
        }

        $publicDir = dirname(__DIR__, 2) . '/public';
        $targetFile = $publicDir . $relativeFolder . $safeFileName;
        $realUploadRoot = realpath($publicDir . '/assets/uploads/consegne');
        $realTarget = realpath($targetFile);
        $normalizedUploadRoot = $realUploadRoot === false ? '' : str_replace('\\', '/', $realUploadRoot);
        $normalizedTarget = $realTarget === false ? '' : str_replace('\\', '/', $realTarget);
        if ($normalizedUploadRoot === '' || $normalizedTarget === '' || strpos($normalizedTarget, rtrim($normalizedUploadRoot, '/') . '/') !== 0 || !is_file($realTarget)) {
            return ['success' => false, 'message' => 'File non trovato.'];
        }

        if (!@unlink($realTarget)) {
            return ['success' => false, 'message' => 'Errore durante l\'eliminazione del file.'];
        }

        $deliveryFolder = dirname($realTarget);
        $remainingFiles = array_diff(scandir($deliveryFolder) ?: [], ['.', '..']);
        if (count($remainingFiles) === 0) {
            @rmdir($deliveryFolder);
            Database::getConnection()
                ->prepare('UPDATE ct_consegne_studenti SET file_consegnato = :file_consegnato WHERE id_consegna = :id_consegna')
                ->execute([
                    'file_consegnato' => '',
                    'id_consegna' => (int) ($delivery['id_consegna'] ?? 0),
                ]);
        }

        return ['success' => true, 'message' => 'student.quest.exercise.file_deleted'];
    }

    private function findExerciseInChapterForStudent(int $classId, int $chapterId, int $exerciseId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT e.*, t.tipo,
                    (select tr.traduzione from ct_traduzioni tr where tr.nome_tabella = \'ct_tipi_esercizio\' and tr.nome_campo = \'tipo\' and tr.lingua = \'en\' and tr.fk_collegamento = t.id_tipo_esercizio) as tipo_en,
                    a.nome_argomento
             FROM ct_esercizi e
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
             INNER JOIN ct_tipi_esercizio t ON t.id_tipo_esercizio = e.tipo_esercizio
             INNER JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = e.id_esercizio
             WHERE e.id_esercizio = :id_esercizio
               AND eq.fk_capitolo = :id_capitolo
               AND cea.fk_classe = :id_classe
               AND cea.attivo = 1
             LIMIT 1'
        );
        $stmt->execute(['id_esercizio' => $exerciseId, 'id_capitolo' => $chapterId, 'id_classe' => $classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function findStudentDelivery(int $exerciseId, int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT cs.*, er.testo_risposta
             FROM ct_consegne_studenti cs
             LEFT JOIN ct_esercizio_risposte er
               ON er.fk_consegna = cs.id_consegna
              AND er.fk_domanda IS NULL
             WHERE cs.fk_esercizio = :fk_esercizio
               AND cs.fk_studente = :fk_studente
             ORDER BY cs.id_consegna DESC
             LIMIT 1'
        );
        $stmt->execute(['fk_esercizio' => $exerciseId, 'fk_studente' => $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function hasAssignedQuizQuestions(int $exerciseId, int $studentId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_esercizio_domande
             WHERE fk_esercizio = :fk_esercizio
               AND fk_studente = :fk_studente'
        );
        $stmt->execute([
            'fk_esercizio' => $exerciseId,
            'fk_studente' => $studentId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function countActiveExercisesInChapter(int $questId, int $chapterId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = eq.fk_esercizio
             WHERE cq.fk_quest = :id_quest
               AND cq.fk_capitolo = :id_capitolo
               AND cea.attivo = 1'
        );
        $stmt->execute([
            'id_quest' => $questId,
            'id_capitolo' => $chapterId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function countCompletedExercisesInChapter(int $questId, int $chapterId, int $studentId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_consegne_studenti cs
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = cs.fk_esercizio
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = eq.fk_capitolo
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = eq.fk_esercizio
             WHERE cq.fk_quest = :id_quest
               AND eq.fk_capitolo = :id_capitolo
               AND cs.fk_studente = :id_studente
               AND cea.attivo = 1'
        );
        $stmt->execute([
            'id_quest' => $questId,
            'id_capitolo' => $chapterId,
            'id_studente' => $studentId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function countActiveExercisesBeforeProgressive(int $questId, int $progressive): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = eq.fk_esercizio
             WHERE cq.fk_quest = :id_quest
               AND cq.progressivo < :progressivo
               AND cea.attivo = 1'
        );
        $stmt->execute([
            'id_quest' => $questId,
            'progressivo' => $progressive,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function countCompletedExercisesBeforeProgressive(int $questId, int $progressive, int $studentId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_consegne_studenti cs
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = cs.fk_esercizio
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = eq.fk_capitolo
             INNER JOIN ct_classi_esercizi_attivi cea ON cea.fk_esercizio = eq.fk_esercizio
             WHERE cq.fk_quest = :id_quest
               AND cq.progressivo < :progressivo
               AND cs.fk_studente = :id_studente
               AND cea.attivo = 1'
        );
        $stmt->execute([
            'id_quest' => $questId,
            'progressivo' => $progressive,
            'id_studente' => $studentId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function hasCompletedPreviousExercise(int $studentId, int $chapterId, int $progressive): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_consegne_studenti cs
             WHERE cs.fk_studente = :id_studente
               AND cs.fk_esercizio = (
                    SELECT e.id_esercizio
                    FROM ct_esercizi e
                    INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
                    WHERE eq.fk_capitolo = :id_capitolo
                      AND eq.progressivo < :progressivo
                    ORDER BY eq.progressivo DESC
                    LIMIT 1
               )'
        );
        $stmt->execute([
            'id_studente' => $studentId,
            'id_capitolo' => $chapterId,
            'progressivo' => $progressive,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function canAccessExercise(int $lockMode, int $studentId, int $chapterId, int $exerciseId): bool
    {
        if ($lockMode !== 1) {
            return true;
        }

        $stmt = Database::getConnection()->prepare('SELECT progressivo FROM ct_esercizi_quest WHERE fk_capitolo = :fk_capitolo AND fk_esercizio = :fk_esercizio LIMIT 1');
        $stmt->execute(['fk_capitolo' => $chapterId, 'fk_esercizio' => $exerciseId]);
        $progressive = (int) $stmt->fetchColumn();
        if ($progressive <= 1) {
            return true;
        }

        $prev = Database::getConnection()->prepare('SELECT fk_esercizio FROM ct_esercizi_quest WHERE fk_capitolo = :fk_capitolo AND progressivo = :progressivo LIMIT 1');
        $prev->execute(['fk_capitolo' => $chapterId, 'progressivo' => $progressive - 1]);
        $prevExercise = (int) $prev->fetchColumn();
        if ($prevExercise <= 0) {
            return true;
        }

        $check = Database::getConnection()->prepare('SELECT COUNT(*) FROM ct_consegne_studenti WHERE fk_esercizio = :fk_esercizio AND fk_studente = :fk_studente');
        $check->execute(['fk_esercizio' => $prevExercise, 'fk_studente' => $studentId]);
        return (int) $check->fetchColumn() > 0;
    }

    private function loadExerciseMaterials(int $exerciseId, int $fallbackMaterialId): array
    {
        $materials = [];
        $stmt = Database::getConnection()->prepare(
            'SELECT em.fk_materiale, em.link, m.nome_materiale, m.link_materiale
             FROM ct_esercizio_materiali em
             LEFT JOIN ct_materiali m ON m.id_materiale = em.fk_materiale
             WHERE em.fk_esercizio = :fk_esercizio'
        );
        $stmt->execute(['fk_esercizio' => $exerciseId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
            if (!empty($row['fk_materiale']) && !empty($row['nome_materiale'])) {
                $materials[] = ['nome' => (string) $row['nome_materiale'], 'url' => '/' . ltrim((string) $row['link_materiale'], '/')];
            }
            if (!empty($row['link'])) {
                $materials[] = ['nome' => (string) $row['link'], 'url' => (string) $row['link']];
            }
        }

        if (count($materials) === 0 && $fallbackMaterialId > 0) {
            $stmt = Database::getConnection()->prepare('SELECT nome_materiale, link_materiale FROM ct_materiali WHERE id_materiale = :id_materiale LIMIT 1');
            $stmt->execute(['id_materiale' => $fallbackMaterialId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            if ($row !== null) {
                $materials[] = ['nome' => (string) $row['nome_materiale'], 'url' => '/' . ltrim((string) $row['link_materiale'], '/')];
            }
        }

        return $materials;
    }

    private function prepareRandomQuestionsForStudent(int $exerciseId, int $studentId, int $topicId, int $numQuestions, string $quizType): void
    {
        Database::getConnection()->prepare('DELETE FROM ct_esercizio_domande WHERE fk_esercizio = :fk_esercizio AND fk_studente = :fk_studente')
            ->execute(['fk_esercizio' => $exerciseId, 'fk_studente' => $studentId]);

        $condition = $quizType === 'crocette' ? 'd.fk_tipo_domanda = 2' : '(d.fk_tipo_domanda = 2 OR d.fk_tipo_domanda = 1)';
        $stmt = Database::getConnection()->query(
            'SELECT d.id_domanda FROM ct_domande d WHERE d.fk_argomento = ' . (int) $topicId . ' AND ' . $condition . ' ORDER BY RAND() LIMIT ' . (int) $numQuestions
        );
        $questionIds = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        $insert = Database::getConnection()->prepare('INSERT INTO ct_esercizio_domande (fk_esercizio, fk_domanda, fk_studente) VALUES (:fk_esercizio, :fk_domanda, :fk_studente)');
        foreach ($questionIds as $idDomanda) {
            $insert->execute(['fk_esercizio' => $exerciseId, 'fk_domanda' => (int) $idDomanda, 'fk_studente' => $studentId]);
        }
    }

    private function loadQuizQuestionsWithAnswers(int $exerciseId, int $studentId, int $deliveryId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.*
             FROM ct_esercizio_domande ed
             INNER JOIN ct_domande d ON d.id_domanda = ed.fk_domanda
             WHERE ed.fk_esercizio = :fk_esercizio
               AND ed.fk_studente = :fk_studente'
        );
        $stmt->execute(['fk_esercizio' => $exerciseId, 'fk_studente' => $studentId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($questions as $index => $question) {
            $rid = (int) ($question['id_domanda'] ?? 0);
            $ans = Database::getConnection()->prepare('SELECT * FROM ct_risposte WHERE fk_domanda = :fk_domanda');
            $ans->execute(['fk_domanda' => $rid]);
            $questions[$index]['answers'] = $ans->fetchAll(PDO::FETCH_ASSOC) ?: [];

            $given = Database::getConnection()->prepare(
                'SELECT * FROM ct_esercizio_risposte WHERE fk_esercizio = :fk_esercizio AND fk_studente = :fk_studente AND fk_domanda = :fk_domanda' . ($deliveryId > 0 ? ' AND fk_consegna = :fk_consegna' : '') . ' LIMIT 1'
            );
            $params = ['fk_esercizio' => $exerciseId, 'fk_studente' => $studentId, 'fk_domanda' => $rid];
            if ($deliveryId > 0) {
                $params['fk_consegna'] = $deliveryId;
            }
            $given->execute($params);
            $questions[$index]['student_answer'] = $given->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return $questions;
    }

    private function saveOpenAnswer(int $studentId, int $exerciseId, int $deliveryId, string $text): void
    {
        $stmt = Database::getConnection()->prepare('SELECT id_ese_risp FROM ct_esercizio_risposte WHERE fk_studente = :fk_studente AND fk_esercizio = :fk_esercizio AND fk_consegna = :fk_consegna LIMIT 1');
        $stmt->execute(['fk_studente' => $studentId, 'fk_esercizio' => $exerciseId, 'fk_consegna' => $deliveryId]);
        $exists = $stmt->fetchColumn();
        if ($exists) {
            Database::getConnection()->prepare('UPDATE ct_esercizio_risposte SET testo_risposta = :testo_risposta, data_risposta = :data_risposta WHERE fk_risposta = :id_risposta')
                ->execute(['testo_risposta' => $this->sanitizeRichText($text), 'data_risposta' => date('Y-m-d'), 'id_risposta' => (int) $exists]);
            return;
        }
        Database::getConnection()->prepare('INSERT INTO ct_esercizio_risposte (fk_studente, fk_esercizio, fk_consegna, testo_risposta, data_risposta) VALUES (:fk_studente, :fk_esercizio, :fk_consegna, :testo_risposta, :data_risposta)')
            ->execute(['fk_studente' => $studentId, 'fk_esercizio' => $exerciseId, 'fk_consegna' => $deliveryId, 'testo_risposta' => $this->sanitizeRichText($text), 'data_risposta' => date('Y-m-d')]);
    }

    private function sanitizeRichText(string $content): string
    {
        $decoded = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $withoutScripts = preg_replace('#<(script|style)\b[^>]*>(.*?)</\1>#is', '', $decoded);
        $clean = strip_tags((string) $withoutScripts, '<p><br><strong><b><em><i><u><ul><ol><li><a><blockquote><span>');

        return trim($clean);
    }

    private function saveQuizAnswers(int $studentId, int $exerciseId, int $deliveryId, array $input): void
    {
        Database::getConnection()->prepare('DELETE FROM ct_esercizio_risposte WHERE fk_consegna = :fk_consegna')
            ->execute(['fk_consegna' => $deliveryId]);

        $questions = Database::getConnection()->prepare('SELECT d.id_domanda, d.fk_tipo_domanda FROM ct_esercizio_domande ed INNER JOIN ct_domande d ON d.id_domanda = ed.fk_domanda WHERE ed.fk_esercizio = :fk_esercizio AND ed.fk_studente = :fk_studente');
        $questions->execute(['fk_esercizio' => $exerciseId, 'fk_studente' => $studentId]);
        foreach ($questions->fetchAll(PDO::FETCH_ASSOC) ?: [] as $question) {
            $idDomanda = (int) ($question['id_domanda'] ?? 0);
            if ((int) ($question['fk_tipo_domanda'] ?? 0) === 2) {
                $fkRisposta = isset($input['rispdom_' . $idDomanda]) ? (int) $input['rispdom_' . $idDomanda] : 0;
                Database::getConnection()->prepare('INSERT INTO ct_esercizio_risposte (fk_studente, fk_esercizio, fk_consegna, testo_risposta, data_risposta, fk_domanda, fk_risposta) VALUES (:fk_studente,:fk_esercizio,:fk_consegna,:testo_risposta,:data_risposta,:fk_domanda,:fk_risposta)')
                    ->execute(['fk_studente' => $studentId, 'fk_esercizio' => $exerciseId, 'fk_consegna' => $deliveryId, 'testo_risposta' => '', 'data_risposta' => date('Y-m-d'), 'fk_domanda' => $idDomanda, 'fk_risposta' => $fkRisposta]);
            } else {
                $text = (string) ($input['testo_risposta' . $idDomanda] ?? '');
                Database::getConnection()->prepare('INSERT INTO ct_esercizio_risposte (fk_studente, fk_esercizio, fk_consegna, testo_risposta, data_risposta, fk_domanda) VALUES (:fk_studente,:fk_esercizio,:fk_consegna,:testo_risposta,:data_risposta,:fk_domanda)')
                    ->execute(['fk_studente' => $studentId, 'fk_esercizio' => $exerciseId, 'fk_consegna' => $deliveryId, 'testo_risposta' => $this->sanitizeRichText($text), 'data_risposta' => date('Y-m-d'), 'fk_domanda' => $idDomanda]);
            }
        }
    }

    private function saveUploadedExerciseFiles(int $studentId, int $deliveryId, array $files): ?string
    {
        if (!isset($files['name'])) {
            return null;
        }

        $names = is_array($files['name']) ? $files['name'] : [$files['name']];
        $tmpNames = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
        $errors = is_array($files['error']) ? $files['error'] : [$files['error']];

        $baseDir = dirname(__DIR__, 2) . '/public/assets/uploads/consegne';
        if (!is_dir($baseDir) && !mkdir($baseDir, 0775, true) && !is_dir($baseDir)) {
            return null;
        }
        $relativeFolder = '/assets/uploads/consegne/s' . $studentId . '_c' . $deliveryId;
        $targetDir = dirname(__DIR__, 2) . '/public' . $relativeFolder;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return null;
        }

        foreach ($names as $i => $name) {
            if ((int) ($errors[$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }
            $tmp = (string) ($tmpNames[$i] ?? '');
            if ($tmp === '' || !is_uploaded_file($tmp)) {
                continue;
            }
            $clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', (string) $name) ?: ('file_' . $i);
            $ext = strtolower((string) pathinfo($clean, PATHINFO_EXTENSION));
            if ($ext === 'php') {
                $clean .= '.txt';
            }
            @move_uploaded_file($tmp, $targetDir . '/' . $clean);
        }

        return $relativeFolder . '/';
    }

    private function registerNewDeliveryRewards(int $studentId, int $classId): ?string
    {
        Database::getConnection()->prepare('UPDATE ct_squadra_studente SET tot_ese_consegnati = tot_ese_consegnati + 1 WHERE fk_studente = :fk_studente')
            ->execute(['fk_studente' => $studentId]);

        $stmt = Database::getConnection()->prepare('SELECT esercizi_cons FROM ct_studenti_classi WHERE fk_studente = :fk_studente AND fk_classe = :fk_classe LIMIT 1');
        $stmt->execute(['fk_studente' => $studentId, 'fk_classe' => $classId]);
        $cons = (int) $stmt->fetchColumn() + 1;
        $forziere = null;
        if ($cons >= 3) {
            $cons -= 3;
            $forziere = $this->assignChest($studentId);
            $eventResults = (new PluginEventBus())->dispatch(PluginEventBus::EVENT_CHEST_EARNED, [
                'class_id' => $classId,
                'student_id' => $studentId,
                'rarity' => $forziere,
                'quantity' => 1,
                'source' => 'quest.delivery_counter',
            ]);
            $extraChestCount = $this->countExtraChestsFromEventResults($eventResults);
            if ($extraChestCount > 0) {
                $forziere .= '+' . $extraChestCount;
            }

            (new PluginEventBus())->dispatch(PluginEventBus::EVENT_STUDENT_REWARD_ASSIGNED, [
                'class_id' => $classId,
                'student_id' => $studentId,
                'reward_type' => 'chest',
                'rarity' => $forziere,
                'quantity' => 1 + $extraChestCount,
                'source' => 'quest.delivery_counter',
            ]);
        }
        Database::getConnection()->prepare('UPDATE ct_studenti_classi SET esercizi_cons = :esercizi_cons WHERE fk_studente = :fk_studente AND fk_classe = :fk_classe')
            ->execute(['esercizi_cons' => $cons, 'fk_studente' => $studentId, 'fk_classe' => $classId]);

        return $forziere;
    }

    private function countExtraChestsFromEventResults(array $eventResults): int
    {
        $count = 0;
        foreach ($eventResults as $eventResult) {
            $result = is_array($eventResult['result'] ?? null) ? $eventResult['result'] : [];
            $extraChests = is_array($result['extra_chests'] ?? null) ? $result['extra_chests'] : [];
            $count += count($extraChests);
        }
        return $count;
    }

    private function assignChest(int $studentId): string
    {
        $roll = random_int(1, 100);
        $rarita = $roll <= 45 ? 'comune' : ($roll <= 75 ? 'non comune' : ($roll <= 90 ? 'raro' : ($roll <= 97 ? 'epico' : 'leggendario')));
        Database::getConnection()->prepare('INSERT INTO ct_forzieri_vinti (livello_rarita, fk_studente, aperto) VALUES (:livello_rarita, :fk_studente, 0)')
            ->execute(['livello_rarita' => $rarita, 'fk_studente' => $studentId]);
        return $rarita;
    }

    private function insertTeacherAlertForDelivery(int $classId, int $studentId, int $questId, int $chapterId, int $exerciseId): array
    {
        $student = Database::getConnection()->prepare('SELECT u.nome, u.cognome FROM ct_studenti s INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente WHERE s.id_studente = :id_studente LIMIT 1');
        $student->execute(['id_studente' => $studentId]);
        $stud = $student->fetch(PDO::FETCH_ASSOC) ?: ['nome' => '', 'cognome' => ''];

        $ese = Database::getConnection()->prepare('SELECT nome_capitolo FROM ct_esercizi WHERE id_esercizio = :id_esercizio LIMIT 1');
        $ese->execute(['id_esercizio' => $exerciseId]);
        $nomeEsercizio = (string) ($ese->fetchColumn() ?: '');
        $quest = Database::getConnection()->prepare('SELECT nome_quest FROM ct_quest WHERE id_quest = :id_quest LIMIT 1');
        $quest->execute(['id_quest' => $questId]);
        $nomeQuest = (string) ($quest->fetchColumn() ?: '');
        $cap = Database::getConnection()->prepare('SELECT nome_capitolo FROM ct_capitoli WHERE id_capitolo = :id_capitolo LIMIT 1');
        $cap->execute(['id_capitolo' => $chapterId]);
        $nomeCap = (string) ($cap->fetchColumn() ?: '');

        $testo = sprintf(
            $this->t('student.quest.alert.delivery_completed'),
            trim(((string) $stud['nome']) . ' ' . ((string) $stud['cognome'])),
            $nomeEsercizio,
            $nomeQuest,
            $nomeCap
        );
        $link = '/docenti/quest/' . $questId . '/capitolo/' . $chapterId . '/esercizi/' . $exerciseId . '/consegne/' . $studentId;
        Database::getConnection()->prepare('INSERT INTO ct_alerts (fk_classe, data_alert, letto, testo, tipologia, link, doc_stud, fk_studente) VALUES (:fk_classe, :data_alert, 0, :testo, :tipologia, :link, 0, 0)')
            ->execute(['fk_classe' => $classId, 'data_alert' => date('Y-m-d'), 'testo' => $testo, 'tipologia' => 'Esercizi', 'link' => $link]);

        return ['text' => $testo, 'link' => $link];
    }

    private function sendTeacherDeliveryEmails(int $classId, string $alertText, string $link): void
    {
        try {
            $this->sendTeacherDeliveryEmailsSafely($classId, $alertText, $link);
        } catch (\Throwable) {
            // Email notifications must not block a saved delivery.
        }
    }

    private function sendTeacherDeliveryEmailsSafely(int $classId, string $alertText, string $link): void
    {
        $recipients = $this->getClassTeacherMailRecipients($classId);
        if ($recipients === []) {
            return;
        }

        $mailService = new MailService();
        $subject = $this->t('student.quest.mail.delivery_completed.subject');
        $safeText = htmlspecialchars($alertText, ENT_QUOTES, 'UTF-8');
        $safeLink = htmlspecialchars($this->absoluteUrl($link), ENT_QUOTES, 'UTF-8');
        $body = sprintf($this->t('student.quest.mail.delivery_completed.body'), $safeText, $safeLink);

        foreach ($recipients as $recipient) {
            $email = (string) ($recipient['email'] ?? '');
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $mailService->sendMail($body, $subject, '', $email);
        }
    }

    private function getClassTeacherMailRecipients(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT DISTINCT u.email
             FROM ct_utenti u
             INNER JOIN ct_utenti_classi uc ON uc.fk_utente = u.id_utente
             INNER JOIN ct_utenti_tipi ut ON ut.fk_utente = u.id_utente
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE uc.fk_classe = :fk_classe
               AND u.ricevi_mail = 1
               AND u.email <> ""
               AND tu.tipo_utente IN ("docente", "amministratore")'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function absoluteUrl(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
        if ($host === '') {
            return $path;
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            || (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
        $scheme = $isHttps ? 'https' : 'http';

        return $scheme . '://' . $host . '/' . ltrim($path, '/');
    }

    private function t(string $key): string
    {
        return $this->translator->translate($key);
    }
}
