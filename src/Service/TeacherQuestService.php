<?php

namespace App\Service;

use DOMDocument;
use PDO;
use Throwable;
use ZipArchive;

use App\Service\TranslationService;



class TeacherQuestService
{
    private QuestRewardService $questRewardService;
    private StudentDashboardService $studentDashboardService;
    public TranslationService $translator;

    public function __construct()
    {
        $this->questRewardService = new QuestRewardService();
        $this->studentDashboardService = new StudentDashboardService();
        $this->translator=new TranslationService();
    }

    public function getQuestPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'quests' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['quests'] = $this->getQuestsForClass($classId);

        return $data;
    }

    public function saveQuest(array $input, array $files): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = isset($input['id_quest']) ? (int) $input['id_quest'] : 0;
        $questName = trim((string) ($input['nome_quest'] ?? ''));
        $bloccaEsercizi = isset($input['blocca_ese']) ? (int) $input['blocca_ese'] : 1;
        if (!in_array($bloccaEsercizi, [1, 2], true)) {
            $bloccaEsercizi = 1;
        }

        if ($questName === '') {
            return $this->error($this->translator->translate('quest.namerequired'));
        }

        $questImageFile = is_array($files['image_quest'] ?? null) ? $files['image_quest'] : [];
        $mapImageFile = is_array($files['piantina_quest'] ?? null) ? $files['piantina_quest'] : [];

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $existingQuest = null;
            if ($questId > 0) {
                $existingQuest = $this->findQuestById($classId, $questId);
                if ($existingQuest === null) {
                    throw new \RuntimeException($this->translator->translate('quest.notfound'));
                }
                if (trim((string) ($existingQuest['uuid'] ?? '')) === '') {
                    $existingQuest['uuid'] = $this->ensureQuestUuid($questId);
                }
            }

            $questImagePath = $this->resolveQuestImagePath($questImageFile, $questId === 0 ? null : (string) ($existingQuest['image_quest'] ?? ''));
            $mapImagePath = $this->resolveMapImagePath($mapImageFile, $questId === 0 ? null : (string) ($existingQuest['piantina_quest'] ?? ''));

            if ($questId === 0) {
                if ($questImagePath === null) {
                    throw new \RuntimeException($this->translator->translate('quest.imgrequired'));
                }

                if ($mapImagePath === null) {
                    throw new \RuntimeException($this->translator->translate('quest.maprequired'));
                }

                $insert = $pdo->prepare(
                    'INSERT INTO ct_quest (uuid, nome_quest, image_quest, piantina_quest, blocca_ese, originale)
                     VALUES (:uuid, :nome_quest, :image_quest, :piantina_quest, :blocca_ese, 1)'
                );
                $questUuid = $this->generateUuidV4();
                $insert->execute([
                    'uuid' => $questUuid,
                    'nome_quest' => $questName,
                    'image_quest' => $questImagePath,
                    'piantina_quest' => $mapImagePath,
                    'blocca_ese' => $bloccaEsercizi,
                ]);

                $newQuestId = (int) $pdo->lastInsertId();
                $this->ensureQuestEditorImageDirectory($newQuestId, $questUuid);

                $bindToClass = $pdo->prepare(
                    'INSERT INTO ct_classi_quest (fk_classe, fk_quest)
                     VALUES (:fk_classe, :fk_quest)'
                );
                $bindToClass->execute([
                    'fk_classe' => $classId,
                    'fk_quest' => $newQuestId,
                ]);

                $pdo->commit();

                return [
                    'success' => true,
                    'message' => $this->translator->translate('quest.created'),
                ];
            }

            $params = [
                'nome_quest' => $questName,
                'blocca_ese' => $bloccaEsercizi,
                'id_quest' => $questId,
            ];

            $sql = 'UPDATE ct_quest SET nome_quest = :nome_quest, blocca_ese = :blocca_ese';
            if ($questImagePath !== null) {
                $sql .= ', image_quest = :image_quest';
                $params['image_quest'] = $questImagePath;
            }

            if ($mapImagePath !== null) {
                $sql .= ', piantina_quest = :piantina_quest';
                $params['piantina_quest'] = $mapImagePath;
            }

            $sql .= ' WHERE id_quest = :id_quest';

            $update = $pdo->prepare($sql);
            $update->execute($params);
            $this->ensureQuestEditorImageDirectory($questId, (string) ($existingQuest['uuid'] ?? ''));

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('quest.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function deleteQuest(int $questId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($questId <= 0) {
            return $this->error($this->translator->translate('quest.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('quest.notfound'));
        }

        $delete = Database::getConnection()->prepare(
            'DELETE FROM ct_classi_quest
             WHERE fk_quest = :fk_quest
               AND fk_classe = :fk_classe'
        );
        $delete->execute([
            'fk_quest' => $questId,
            'fk_classe' => $classId,
        ]);

        $delete2 = Database::getConnection()->prepare(
            'UPDATE ct_classi_esercizi_attivi SET attivo=0 WHERE fk_capitolo in 
            (select fk_capitolo from ct_capitoli_quest where fk_quest=:fk_quest) 
            AND fk_classe = :fk_classe'
        );
        $delete2->execute([
            'fk_quest' => $questId,
            'fk_classe' => $classId,
        ]);

        return [
            'success' => true,
            'message' => $this->translator->translate('quest.canceled'),
        ];
    }


    public function getQuestMapPageData(int $questId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'quest' => null,
            'chapters' => [],
            'nextProgressive' => 1,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['quest'] = $this->findQuestById($classId, $questId);
        if ($data['quest'] === null) {
            return $data;
        }

        $data['chapters'] = $this->getQuestChapters($questId);
        $data['nextProgressive'] = $this->getNextChapterProgressive($questId);

        return $data;
    }

    public function getUnevaluatedDeliveriesPageData(int $questId): array
    {
        $data = $this->getQuestMapPageData($questId);
        $data['deliveries'] = [];

        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK || ($data['quest'] ?? null) === null) {
            return $data;
        }

        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            return $data;
        }

        $data['deliveries'] = $this->findUnevaluatedDeliveriesForQuest($classId, $questId);

        return $data;
    }

    public function saveDeliveryProblem(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $questId = (int) ($input['id_quest'] ?? 0);
        $deliveryId = (int) ($input['id_consegna'] ?? 0);
        $hasProblem = isset($input['problema']) ? 1 : 0;
        $description = trim((string) ($input['descrizione_problema'] ?? ''));
        $redirectUrl = '/docenti/quest/' . $questId . '/consegne-non-valutate';

        if ($questId <= 0 || $deliveryId <= 0) {
            return array_merge($this->error($this->translator->translate('teacher.quest.delivery.problem.invalid')), ['redirectUrl' => $redirectUrl]);
        }

        $classId = $this->getCurrentClassIdOrFail();
        $delivery = $this->findDeliveryInQuest($classId, $questId, $deliveryId);
        if ($delivery === null) {
            return array_merge($this->error($this->translator->translate('teacher.quest.delivery.not_found')), ['redirectUrl' => $redirectUrl]);
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $pdo->prepare(
                'UPDATE ct_consegne_studenti
                 SET problema = :problema,
                     descrizione_problema = :descrizione_problema
                 WHERE id_consegna = :id_consegna'
            )->execute([
                'problema' => $hasProblem,
                'descrizione_problema' => $hasProblem === 1 ? $description : '',
                'id_consegna' => $deliveryId,
            ]);

            if ($hasProblem === 1) {
                $message = sprintf(
                    $this->translator->translate('student.quest.alert.delivery_problem'),
                    (string) ($delivery['nome_esercizio'] ?? ''),
                    $description !== '' ? $description : $this->translator->translate('student.quest.alert.delivery_problem.no_description')
                );

                $this->insertStudentAlert(
                    $classId,
                    (int) ($delivery['id_studente'] ?? 0),
                    $message,
                    'ProblemaConsegna',
                    '/studenti/quest/' . $questId . '/capitoli/' . (int) ($delivery['id_capitolo'] ?? 0) . '/esercizi/' . (int) ($delivery['id_esercizio'] ?? 0)
                );
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return array_merge($this->error($exception->getMessage()), ['redirectUrl' => $redirectUrl]);
        }

        return [
            'success' => true,
            'message' => $this->translator->translate('teacher.quest.delivery.problem.saved'),
            'redirectUrl' => $redirectUrl,
        ];
    }

    public function createChapter(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = isset($input['id_quest']) ? (int) $input['id_quest'] : 0;
        $chapterName = trim((string) ($input['nome_capitolo'] ?? ''));
        $coordX = isset($input['coord_x']) ? (int) $input['coord_x'] : -1;
        $coordY = isset($input['coord_y']) ? (int) $input['coord_y'] : -1;
        $progressive = isset($input['progressivo']) ? (int) $input['progressivo'] : 0;

        if ($questId <= 0) {
            return $this->error($this->translator->translate('quest.invalid'));
        }

        if ($chapterName === '') {
            return $this->error($this->translator->translate('quest.chaptername'));
        }

        if ($coordX < 0 || $coordY < 0) {
            return $this->error($this->translator->translate('quest.coord.invalid'));
        }

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('quest.notfound'));
        }

        if ($progressive <= 0) {
            $progressive = $this->getNextChapterProgressive($questId);
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $insertChapter = $pdo->prepare(
                'INSERT INTO ct_capitoli (uuid, nome_capitolo, coord_x, coord_y)
                 VALUES (:uuid, :nome_capitolo, :coord_x, :coord_y)'
            );
            $insertChapter->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_capitolo' => $chapterName,
                'coord_x' => $coordX,
                'coord_y' => $coordY,
            ]);

            $chapterId = (int) $pdo->lastInsertId();

            $bindChapter = $pdo->prepare(
                'INSERT INTO ct_capitoli_quest (fk_quest, fk_capitolo, progressivo)
                 VALUES (:fk_quest, :fk_capitolo, :progressivo)'
            );
            $bindChapter->execute([
                'fk_quest' => $questId,
                'fk_capitolo' => $chapterId,
                'progressivo' => $progressive,
            ]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('quest.chaptercreated'),
                'chapterId' => $chapterId,
                'redirectUrl' => '/docenti/quest/' . $questId . '/capitoli/' . $chapterId,
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function updateChapter(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = isset($input['id_quest']) ? (int) $input['id_quest'] : 0;
        $chapterId = isset($input['id_capitolo']) ? (int) $input['id_capitolo'] : 0;
        $chapterName = trim((string) ($input['nome_capitolo'] ?? ''));
        $coordX = isset($input['coord_x']) ? (int) $input['coord_x'] : -1;
        $coordY = isset($input['coord_y']) ? (int) $input['coord_y'] : -1;
        $progressive = isset($input['progressivo']) ? (int) $input['progressivo'] : 0;

        if ($questId <= 0 || $chapterId <= 0) {
            return $this->error($this->translator->translate('quest.chapterinvalid'));
        }

        if ($chapterName === '') {
            return $this->error($this->translator->translate('quest.chaptername'));
        }

        if ($coordX < 0 || $coordY < 0) {
            return $this->error($this->translator->translate('quest.coordinvalid'));
        }

        if ($progressive <= 0) {
            return $this->error($this->translator->translate('quest.progressiveinvalid'));
        }

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('quest.notfound'));
        }

        $chapter = $this->findChapterInQuest($questId, $chapterId);
        if ($chapter === null) {
            return $this->error($this->translator->translate('quest.chapter.notfound'));
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $updateChapter = $pdo->prepare(
                'UPDATE ct_capitoli
                 SET nome_capitolo = :nome_capitolo,
                     coord_x = :coord_x,
                     coord_y = :coord_y
                 WHERE id_capitolo = :id_capitolo'
            );
            $updateChapter->execute([
                'nome_capitolo' => $chapterName,
                'coord_x' => $coordX,
                'coord_y' => $coordY,
                'id_capitolo' => $chapterId,
            ]);

            $updateProgressive = $pdo->prepare(
                'UPDATE ct_capitoli_quest
                 SET progressivo = :progressivo
                 WHERE fk_quest = :fk_quest
                   AND fk_capitolo = :fk_capitolo'
            );
            $updateProgressive->execute([
                'progressivo' => $progressive,
                'fk_quest' => $questId,
                'fk_capitolo' => $chapterId,
            ]);

            $pdo->commit();
            return [
                'success' => true,
                'message' => $this->translator->translate('quest.chapter.updated'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function getChapterDetailPageData(int $questId, int $chapterId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'quest' => null,
            'chapter' => null,
            'exercises' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['quest'] = $this->findQuestById($classId, $questId);
        if ($data['quest'] === null) {
            return $data;
        }

        $data['chapter'] = $this->findChapterInQuest($questId, $chapterId);
        if ($data['chapter'] === null) {
            return $data;
        }

        $data['exercises'] = $this->getChapterExercises($classId, $questId, $chapterId);

        return $data;
    }

    public function getAddExercisePageData(int $questId, int $chapterId): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'quest' => null,
            'chapter' => null,
            'exerciseTypes' => [],
            'subjects' => [],
            'topics' => [],
            'materials' => [],
            'exercise' => null,
            'selectedMaterials' => [],
            'materialLinks' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['quest'] = $this->findQuestById($classId, $questId);
        if ($data['quest'] === null) {
            return $data;
        }

        $data['chapter'] = $this->findChapterInQuest($questId, $chapterId);
        if ($data['chapter'] === null) {
            return $data;
        }

        $userId = (new PermissionService())->getCurrentUserId();
        if ($userId === null) {
            return $data;
        }

        $data['exerciseTypes'] = $this->getExerciseTypes();
        $data['subjects'] = $this->getTeacherSubjects($userId);

        $firstSubjectId = (int) ($data['subjects'][0]['id_materia'] ?? 0);
        if ($firstSubjectId > 0) {
            $data['topics'] = $this->getTopicsBySubject($firstSubjectId);
        }

        $firstTopicId = (int) ($data['topics'][0]['id_argomento'] ?? 0);
        if ($firstTopicId > 0) {
            $data['materials'] = $this->getMaterialsByTopic($firstTopicId);
        }

        return $data;
    }

    public function getExerciseEditorPageData(int $questId, int $chapterId, int $exerciseId): array
    {
        $data = $this->getAddExercisePageData($questId, $chapterId);
        if (($data['quest'] ?? null) === null || ($data['chapter'] ?? null) === null) {
            return $data;
        }

        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            return $data;
        }

        $exercise = $this->findExerciseInChapter($classId, $questId, $chapterId, $exerciseId);
        if ($exercise === null) {
            return $data;
        }

        $materialBindings = $this->getExerciseMaterialBindings($exerciseId);
        $selectedMaterials = [];
        $materialLinks = [];
        foreach ($materialBindings as $binding) {
            $materialId = (int) ($binding['fk_materiale'] ?? 0);
            $link = trim((string) ($binding['link'] ?? ''));
            if ($materialId > 0) {
                $selectedMaterials[] = $materialId;
            }
            if ($link !== '') {
                $materialLinks[] = $link;
            }
        }

        if (count($selectedMaterials) === 0 && (int) ($exercise['fk_materiale'] ?? 0) > 0) {
            $selectedMaterials[] = (int) $exercise['fk_materiale'];
        }

        $topicId = (int) ($exercise['fk_argomento'] ?? 0);
        if ($topicId > 0) {
            $data['materials'] = $this->getMaterialsByTopic($topicId);
            $subjectId = $this->findSubjectIdByTopic($topicId);
            if ($subjectId > 0) {
                $data['topics'] = $this->getTopicsBySubject($subjectId);
                $exercise['fk_materia'] = $subjectId;
            }
        }

        $data['exercise'] = $exercise;
        $data['selectedMaterials'] = array_values(array_unique($selectedMaterials));
        $data['materialLinks'] = array_values(array_unique($materialLinks));

        return $data;
    }

    public function saveExercise(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = (int) ($input['id_quest'] ?? 0);
        $chapterId = (int) ($input['id_capitolo'] ?? 0);

        if ($questId <= 0 || $chapterId <= 0) {
            return $this->error($this->translator->translate('quest.chapterinvalid'));
        }

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('quest.notfound'));
        }

        if ($this->findChapterInQuest($questId, $chapterId) === null) {
            return $this->error($this->translator->translate('quest.chapter.notfound'));
        }

        $name = trim((string) ($input['nome_capitolo'] ?? ''));
        $exerciseType = (int) ($input['tipo_esercizio'] ?? 0);
        $difficulty = isset($input['livello_diff']) && $input['livello_diff'] !== '' ? (int) $input['livello_diff'] : 1;
        $topicId = (int) ($input['argomento'] ?? 0);
        $numQuestions = isset($input['num_domande']) && $input['num_domande'] !== '' ? (int) $input['num_domande'] : 0;
        $xpPoints = isset($input['xp_points']) && $input['xp_points'] !== '' ? (int) $input['xp_points'] : null;
        $money = isset($input['money']) && $input['money'] !== '' ? (int) $input['money'] : null;
        $story = htmlentities((string) ($input['story'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $exerciseText = htmlentities((string) ($input['testo_esercizio'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $linksRaw = (string) ($input['link_materiali'] ?? '');

        if ($name === '') {
            return $this->error($this->translator->translate('teacher.quest.exercise.name_required'));
        }

        if ($exerciseType <= 0 || $topicId <= 0) {
            return $this->error($this->translator->translate('teacher.quest.exercise.required_fields'));
        }

        if ($exerciseType === 2) {
            $numQuestions = min($numQuestions, $this->countQuestionsByTopicAndType($topicId, 'fk_tipo_domanda=2'));
        } elseif ($exerciseType === 4) {
            $numQuestions = min($numQuestions, $this->countQuestionsByTopicAndType($topicId, 'fk_tipo_domanda IN (1,2)'));
        }

        $selectedMaterials = array_values(array_unique(array_filter(
            array_map(static fn ($value): int => (int) $value, is_array($input['materiali'] ?? null) ? $input['materiali'] : []),
            static fn (int $value): bool => $value > 0
        )));

        $links = $this->extractValidLinks($linksRaw);
        $legacyMaterialId = count($selectedMaterials) > 0 ? $selectedMaterials[0] : 0;

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $insertExercise = $pdo->prepare(
                'INSERT INTO ct_esercizi
                (uuid, testo_esercizio, punti_esperienza, storia_esercizio, fk_argomento, tipo_esercizio, nome_capitolo, num_domande, monete_guadagnate, fk_materiale, livello_diff)
                 VALUES
                (:uuid, :testo_esercizio, :punti_esperienza, :storia_esercizio, :fk_argomento, :tipo_esercizio, :nome_capitolo, :num_domande, :monete_guadagnate, :fk_materiale, :livello_diff)'
            );
            $insertExercise->execute([
                'uuid' => $this->generateUuidV4(),
                'testo_esercizio' => $exerciseText,
                'punti_esperienza' => $xpPoints,
                'storia_esercizio' => $story,
                'fk_argomento' => $topicId,
                'tipo_esercizio' => $exerciseType,
                'nome_capitolo' => $name,
                'num_domande' => $numQuestions,
                'monete_guadagnate' => $money,
                'fk_materiale' => $legacyMaterialId,
                'livello_diff' => $difficulty,
            ]);

            $exerciseId = (int) $pdo->lastInsertId();

            $materialBind = $pdo->prepare(
                'INSERT INTO ct_esercizio_materiali (fk_esercizio, fk_materiale, link)
                 VALUES (:fk_esercizio, :fk_materiale, :link)'
            );

            foreach ($selectedMaterials as $materialId) {
                $materialBind->execute([
                    'fk_esercizio' => $exerciseId,
                    'fk_materiale' => $materialId,
                    'link' => null,
                ]);
            }

            foreach ($links as $link) {
                $materialBind->execute([
                    'fk_esercizio' => $exerciseId,
                    'fk_materiale' => null,
                    'link' => $link,
                ]);
            }

            $progressive = $this->getNextExerciseProgressive($chapterId);

            $bindExercise = $pdo->prepare(
                'INSERT INTO ct_esercizi_quest (fk_capitolo, fk_esercizio, progressivo)
                 VALUES (:fk_capitolo, :fk_esercizio, :progressivo)'
            );
            $bindExercise->execute([
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
                'progressivo' => $progressive,
            ]);

            $activate = $pdo->prepare(
                'INSERT INTO ct_classi_esercizi_attivi (fk_capitolo, fk_esercizio, fk_classe, attivo)
                 VALUES (:fk_capitolo, :fk_esercizio, :fk_classe, 0)'
            );
            $activate->execute([
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
                'fk_classe' => $classId,
            ]);

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.quest.exercise.saved'),
                'redirectUrl' => '/docenti/quest/' . $questId . '/capitoli/' . $chapterId,
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function getTopicsForSubject(int $subjectId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return ['success' => false, 'topics' => [], 'message' => $access['message'] ?? $this->translator->translate('teacher.quest.permission.operation_not_allowed')];
        }

        return [
            'success' => true,
            'topics' => $subjectId > 0 ? $this->getTopicsBySubject($subjectId) : [],
        ];
    }

    public function getMaterialsForTopic(int $topicId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return ['success' => false, 'materials' => [], 'message' => $access['message'] ?? $this->translator->translate('teacher.quest.permission.operation_not_allowed')];
        }

        return [
            'success' => true,
            'materials' => $topicId > 0 ? $this->getMaterialsByTopic($topicId) : [],
        ];
    }

    public function updateExercise(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = (int) ($input['id_quest'] ?? 0);
        $chapterId = (int) ($input['id_capitolo'] ?? 0);
        $exerciseId = (int) ($input['id_esercizio'] ?? 0);

        if ($questId <= 0 || $chapterId <= 0 || $exerciseId <= 0) {
            return $this->error($this->translator->translate('teacher.quest.exercise.invalid_data'));
        }

        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('teacher.quest.not_found'));
        }

        $exercise = $this->findExerciseInChapter($classId, $questId, $chapterId, $exerciseId);
        if ($exercise === null) {
            return $this->error($this->translator->translate('teacher.quest.exercise.not_found_in_chapter'));
        }

        if ((int) ($exercise['attivo'] ?? 0) === 1) {
            return $this->error($this->translator->translate('teacher.quest.exercise.active_not_editable'));
        }

        $name = trim((string) ($input['nome_capitolo'] ?? ''));
        $exerciseType = (int) ($input['tipo_esercizio'] ?? 0);
        $difficulty = isset($input['livello_diff']) && $input['livello_diff'] !== '' ? (int) $input['livello_diff'] : 1;
        $topicId = (int) ($input['argomento'] ?? 0);
        $numQuestions = isset($input['num_domande']) && $input['num_domande'] !== '' ? (int) $input['num_domande'] : 0;
        $xpPoints = isset($input['xp_points']) && $input['xp_points'] !== '' ? (int) $input['xp_points'] : null;
        $money = isset($input['money']) && $input['money'] !== '' ? (int) $input['money'] : null;
        $progressive = isset($input['progressivo']) && $input['progressivo'] !== '' ? (int) $input['progressivo'] : (int) ($exercise['progressivo'] ?? 0);
        $story = htmlentities((string) ($input['story'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $exerciseText = htmlentities((string) ($input['testo_esercizio'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $exerciseText104 = htmlentities((string) ($input['testo_esercizio104'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $linksRaw = (string) ($input['link_materiali'] ?? '');

        if ($name === '' || $exerciseType <= 0 || $topicId <= 0) {
            return $this->error($this->translator->translate('teacher.quest.exercise.required_fields'));
        }

        if ($exerciseType === 2) {
            $numQuestions = min($numQuestions, $this->countQuestionsByTopicAndType($topicId, 'fk_tipo_domanda=2'));
        } elseif ($exerciseType === 4) {
            $numQuestions = min($numQuestions, $this->countQuestionsByTopicAndType($topicId, 'fk_tipo_domanda IN (1,2)'));
        }

        $selectedMaterials = array_values(array_unique(array_filter(
            array_map(static fn ($value): int => (int) $value, is_array($input['materiali'] ?? null) ? $input['materiali'] : []),
            static fn (int $value): bool => $value > 0
        )));
        $links = $this->extractValidLinks($linksRaw);
        $legacyMaterialId = count($selectedMaterials) > 0 ? $selectedMaterials[0] : 0;

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            $update = $pdo->prepare(
                'UPDATE ct_esercizi
                 SET testo_esercizio = :testo_esercizio,
                     punti_esperienza = :punti_esperienza,
                     storia_esercizio = :storia_esercizio,
                     fk_argomento = :fk_argomento,
                     tipo_esercizio = :tipo_esercizio,
                     nome_capitolo = :nome_capitolo,
                     num_domande = :num_domande,
                     monete_guadagnate = :monete_guadagnate,
                     fk_materiale = :fk_materiale,
                     livello_diff = :livello_diff,
                     testo_ese104 = :testo_ese104
                 WHERE id_esercizio = :id_esercizio'
            );
            $update->execute([
                'testo_esercizio' => $exerciseText,
                'punti_esperienza' => $xpPoints,
                'storia_esercizio' => $story,
                'fk_argomento' => $topicId,
                'tipo_esercizio' => $exerciseType,
                'nome_capitolo' => $name,
                'num_domande' => $numQuestions,
                'monete_guadagnate' => $money,
                'fk_materiale' => $legacyMaterialId,
                'livello_diff' => $difficulty,
                'testo_ese104' => $exerciseText104,
                'id_esercizio' => $exerciseId,
            ]);

            $updateProgressive = $pdo->prepare(
                'UPDATE ct_esercizi_quest
                 SET progressivo = :progressivo
                 WHERE fk_capitolo = :fk_capitolo
                   AND fk_esercizio = :fk_esercizio'
            );
            $updateProgressive->execute([
                'progressivo' => $progressive > 0 ? $progressive : 1,
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
            ]);

            $clearMaterials = $pdo->prepare('DELETE FROM ct_esercizio_materiali WHERE fk_esercizio = :fk_esercizio');
            $clearMaterials->execute(['fk_esercizio' => $exerciseId]);

            $materialBind = $pdo->prepare(
                'INSERT INTO ct_esercizio_materiali (fk_esercizio, fk_materiale, link)
                 VALUES (:fk_esercizio, :fk_materiale, :link)'
            );
            foreach ($selectedMaterials as $materialId) {
                $materialBind->execute([
                    'fk_esercizio' => $exerciseId,
                    'fk_materiale' => $materialId,
                    'link' => null,
                ]);
            }
            foreach ($links as $link) {
                $materialBind->execute([
                    'fk_esercizio' => $exerciseId,
                    'fk_materiale' => null,
                    'link' => $link,
                ]);
            }

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->translator->translate('teacher.quest.exercise.updated'),
                'redirectUrl' => '/docenti/quest/' . $questId . '/capitoli/' . $chapterId,
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }
    }

    public function activateExercise(int $questId, int $chapterId, int $exerciseId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $quest = $this->findQuestById($classId, $questId);
        $chapter = $this->findChapterInQuest($questId, $chapterId);
        $exercise = $this->findExerciseInChapter($classId, $questId, $chapterId, $exerciseId);

        if ($quest === null || $chapter === null || $exercise === null) {
            return $this->error($this->translator->translate('teacher.quest.exercise.not_found'));
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $update = $pdo->prepare(
                'UPDATE ct_classi_esercizi_attivi
                 SET attivo = 1
                 WHERE fk_capitolo = :fk_capitolo
                   AND fk_esercizio = :fk_esercizio
                   AND fk_classe = :fk_classe'
            );
            $update->execute([
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
                'fk_classe' => $classId,
            ]);

            $chapterProgressive = (int) ($chapter['progressivo'] ?? 0);
            $chapterName = trim((string) ($chapter['nome_capitolo'] ?? ''));
            $exerciseName = trim((string) ($exercise['nome_capitolo'] ?? ''));
            $questName = trim((string) ($quest['nome_quest'] ?? ''));
            $alertText = sprintf(
                $this->translator->translate('student.quest.alert.exercise_activated'),
                $exerciseName,
                $questName,
                $chapterProgressive,
                $chapterName
            );
            
            $exerciseLink = '/studenti/quest/' . $questId . '/capitoli/' . $chapterId . '/esercizi/' . $exerciseId;

            foreach ($this->getClassStudentIds($classId) as $studentId) {
                $this->insertStudentAlert(
                    $classId,
                    $studentId,
                    $alertText,
                    'AttivazioneEsercizio',
                    $exerciseLink
                );
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }

        return ['success' => true, 'message' => $this->translator->translate('teacher.quest.exercise.activated')];
    }

    public function deleteExercise(int $questId, int $chapterId, int $exerciseId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $exercise = $this->findExerciseInChapter($classId, $questId, $chapterId, $exerciseId);
        if ($exercise === null) {
            return $this->error($this->translator->translate('teacher.quest.exercise.not_found'));
        }

        if ((int) ($exercise['attivo'] ?? 0) === 1) {
            return $this->error($this->translator->translate('teacher.quest.exercise.active_not_deletable'));
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $deleteLinks = $pdo->prepare('DELETE FROM ct_esercizio_materiali WHERE fk_esercizio = :fk_esercizio');
            $deleteLinks->execute(['fk_esercizio' => $exerciseId]);

            $deleteFromQuest = $pdo->prepare(
                'DELETE FROM ct_esercizi_quest
                 WHERE fk_capitolo = :fk_capitolo
                   AND fk_esercizio = :fk_esercizio'
            );
            $deleteFromQuest->execute([
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
            ]);

            $deleteActive = $pdo->prepare(
                'DELETE FROM ct_classi_esercizi_attivi
                 WHERE fk_capitolo = :fk_capitolo
                   AND fk_esercizio = :fk_esercizio
                   AND fk_classe = :fk_classe'
            );
            $deleteActive->execute([
                'fk_capitolo' => $chapterId,
                'fk_esercizio' => $exerciseId,
                'fk_classe' => $classId,
            ]);

            $deleteExercise = $pdo->prepare('DELETE FROM ct_esercizi WHERE id_esercizio = :id_esercizio');
            $deleteExercise->execute(['id_esercizio' => $exerciseId]);

            $pdo->commit();
            return ['success' => true, 'message' => $this->translator->translate('teacher.quest.exercise.deleted')];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }
    }

    public function uploadEditorImage(array $files, array $input = []): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $questId = (int) ($input['quest_id'] ?? $input['id_quest'] ?? 0);
        $quest = $questId > 0 ? $this->findQuestById($classId, $questId) : null;
        if ($questId <= 0 || $quest === null) {
            return $this->error($this->translator->translate('teacher.quest.not_found'));
        }
        $questUuid = (string) ($quest['uuid'] ?? '');
        if (trim($questUuid) === '') {
            $questUuid = $this->ensureQuestUuid($questId);
        }

        $uploadedFile = is_array($files['file'] ?? null) ? $files['file'] : [];
        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->translator->translate('teacher.quest.image.invalid'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->translator->translate('teacher.quest.image.file_invalid'));
        }

        $originalName = (string) ($uploadedFile['name'] ?? 'editor.png');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed, true)) {
            return $this->error($this->translator->translate('teacher.quest.image.format_unsupported'));
        }

        $targetDir = $this->ensureQuestEditorImageDirectory($questId, $questUuid);
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return $this->error($this->translator->translate('teacher.quest.image.editor_folder_create_failed'));
        }

        $targetName = uniqid('exercise_editor_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $targetName;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            return $this->error($this->translator->translate('teacher.quest.image.save_failed'));
        }

        return [
            'location' => $this->getQuestEditorImagePublicPath($questId, $questUuid) . '/' . $targetName,
        ];
    }

    public function getExerciseDeliveriesPageData(int $questId, int $chapterId, int $exerciseId): array
    {
        $data = $this->getExerciseEditorPageData($questId, $chapterId, $exerciseId);
        if (($data['quest'] ?? null) === null || ($data['chapter'] ?? null) === null || ($data['exercise'] ?? null) === null) {
            return $data;
        }

        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            return $data;
        }

        $data['students'] = $this->findStudentDeliveriesForExercise($classId, $exerciseId);
        return $data;
    }

    public function getStudentDeliveryPageData(int $questId, int $chapterId, int $exerciseId, int $studentId): array
    {
        $data = $this->getExerciseDeliveriesPageData($questId, $chapterId, $exerciseId);
        $data['studentDelivery'] = null;
        $data['quizOpenAnswers'] = [];
        $data['quizMultipleChoiceAnswers'] = [];
        $data['quizMultipleChoiceScore'] = null;
        $data['submittedFiles'] = [];

        if ($studentId <= 0 || ($data['exercise'] ?? null) === null) {
            return $data;
        }

        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            return $data;
        }

        $delivery = $this->findStudentDeliveryInExercise($classId, $exerciseId, $studentId);
        if ($delivery === null) {
            return $data;
        }
        
        $data['studentDelivery'] = $delivery;
        $data['quizOpenAnswers'] = $this->getStudentOpenQuizAnswers($exerciseId, $studentId, (int) ($delivery['id_consegna'] ?? 0));
        if ((int) ($delivery['tipo_esercizio'] ?? 0) === 2 or (int) ($delivery['tipo_esercizio'] ?? 0) === 4) {
            $data['quizMultipleChoiceAnswers'] = $this->getStudentMultipleChoiceQuizAnswers($exerciseId, $studentId, (int) ($delivery['id_consegna'] ?? 0));
            $data['quizMultipleChoiceScore'] = $this->buildMultipleChoiceScore($data['quizMultipleChoiceAnswers'], (int) ($delivery['num_domande'] ?? 0));
        }
        $data['submittedFiles'] = $this->buildSubmittedFiles((string) ($delivery['file_consegnato'] ?? ''));

        return $data;
    }

    private function getStudentLevel(int $studentId): int {
        $riga = Database::getConnection()->prepare('select livello from ct_studenti where id_studente=:id_studente');
        $riga->execute(['id_studente' => $studentId]);
        $row=$riga->fetch(PDO::FETCH_ASSOC);
        return isset($row["livello"]) ? (int) $row["livello"] : 0;
    }

    public function saveDeliveryEvaluation(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $questId = (int) ($input['id_quest'] ?? 0);
        $chapterId = (int) ($input['id_capitolo'] ?? 0);
        $exerciseId = (int) ($input['id_esercizio'] ?? 0);
        $studentId = (int) ($input['id_studente'] ?? 0);
        $grade = (int) ($input['valutazione'] ?? 0);

        if ($grade < 1 || $grade > 10) {
            return $this->error($this->translator->translate('teacher.quest.evaluation.range_invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $delivery = $this->findStudentDeliveryInExercise($classId, $exerciseId, $studentId);
        if ($delivery === null || (int) ($delivery['id_consegna'] ?? 0) <= 0) {
            return $this->error($this->translator->translate('teacher.quest.delivery.student_not_found'));
        }

        if ((int) ($delivery['valutato'] ?? 0) === 1) {
            return $this->error($this->translator->translate('teacher.quest.delivery.already_evaluated'));
        }

        $deliveryId = (int) $delivery['id_consegna'];
        $comment = trim((string) ($input['commento'] ?? ''));
        $questionComments = is_array($input['commento_domanda'] ?? null) ? $input['commento_domanda'] : [];
        $studentLevel = $this->getStudentLevel($studentId);
        $ricompense = $this->questRewardService->calculateExerciseRewards($studentLevel, (int) ($delivery['livello_diff'] ?? 1), (int) ($delivery['punti_esperienza'] ?? 0), (int) ($delivery['monete_guadagnate'] ?? 0));

        $xp = $ricompense['xp'];
        $coins = $ricompense['monete'];

        //se la valutazione è uguale o inferiore a 3, allora xp e monete vengono tagliati e vengono dati
        //1/3 delle xp e monete che verrebbero dati altrimenti
        if($grade<=3) {
            $xp=$xp/3;
            $coins=$coins/3;
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $pdo->prepare('UPDATE ct_consegne_studenti SET valutazione = :valutazione, valutato = 1 WHERE id_consegna = :id_consegna')
                ->execute(['valutazione' => $grade, 'id_consegna' => $deliveryId]);

            if ((int) ($delivery['tipo_esercizio'] ?? 0) === 4 && count($questionComments) > 0) {
                $updateQuestion = $pdo->prepare(
                    'UPDATE ct_esercizio_risposte
                     SET commento_prof = :commento_prof
                     WHERE fk_consegna = :fk_consegna
                       AND fk_domanda = :fk_domanda'
                );
                foreach ($questionComments as $questionId => $questionComment) {
                    $questionId = (int) $questionId;
                    if ($questionId <= 0) {
                        continue;
                    }
                    $updateQuestion->execute([
                        'commento_prof' => trim((string) $questionComment),
                        'fk_consegna' => $deliveryId,
                        'fk_domanda' => $questionId,
                    ]);
                }
            } else {
                $pdo->prepare('UPDATE ct_esercizio_risposte SET commento_prof = :commento_prof WHERE fk_consegna = :fk_consegna')
                    ->execute(['commento_prof' => $comment, 'fk_consegna' => $deliveryId]);
            }

            if ($xp > 0) {
                $pdo->prepare('UPDATE ct_studenti SET xp = xp + :xp WHERE id_studente = :id_studente')
                    ->execute(['xp' => $xp, 'id_studente' => $studentId]);

                $this->studentDashboardService->handleLevelUpFromCurrentXp($studentId,$classId);
            }

            if ($coins > 0) {
                $pdo->prepare('UPDATE ct_studenti SET monete = monete + :monete WHERE id_studente = :id_studente')
                    ->execute(['monete' => $coins, 'id_studente' => $studentId]);
            }

            $this->insertStudentAlert(
                $classId,
                $studentId,
                sprintf(
                    $this->translator->translate('student.quest.alert.evaluation_rewards'),
                    $xp,
                    $coins,
                    (string) ($delivery['nome_capitolo'] ?? ''),
                    (string) ($delivery['nome_quest'] ?? '')
                ),
                'ValutazioneEsercizio',
                '/studenti/quest/' . $questId . '/capitoli/' . $chapterId . '/esercizi/' . $exerciseId
            );

            (new PluginEventBus())->dispatch(PluginEventBus::EVENT_DELIVERY_EVALUATED, [
                'class_id' => $classId,
                'student_id' => $studentId,
                'delivery_id' => $deliveryId,
                'quest_id' => $questId,
                'chapter_id' => $chapterId,
                'exercise_id' => $exerciseId,
                'grade' => $grade,
                'xp' => $xp,
                'coins' => $coins,
            ]);

            (new PluginEventBus())->dispatch(PluginEventBus::EVENT_STUDENT_REWARD_ASSIGNED, [
                'class_id' => $classId,
                'student_id' => $studentId,
                'reward_type' => 'quest_evaluation',
                'delivery_id' => $deliveryId,
                'quest_id' => $questId,
                'chapter_id' => $chapterId,
                'exercise_id' => $exerciseId,
                'xp' => $xp,
                'coins' => $coins,
                'source' => 'quest.evaluation',
            ]);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }

        return [
            'success' => true,
            'message' => $this->translator->translate('teacher.quest.delivery.evaluation.saved'),
            'redirectUrl' => '/docenti/quest/' . $questId . '/capitolo/' . $chapterId . '/esercizi/' . $exerciseId . '/consegne',
        ];
    }

    public function suggestGeminiEvaluation(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $exerciseId = (int) ($input['id_esercizio'] ?? 0);
        $studentId = (int) ($input['id_studente'] ?? 0);
        $classId = $this->getCurrentClassIdOrFail();
        $delivery = $this->findStudentDeliveryInExercise($classId, $exerciseId, $studentId);
        if ($delivery === null || (int) ($delivery['id_consegna'] ?? 0) <= 0) {
            return $this->error($this->translator->translate('teacher.quest.delivery.not_found'));
        }

        $teacherId = (new PermissionService())->getCurrentUserId();
        if ($teacherId === null) {
            return $this->error($this->translator->translate('teacher.quest.session.invalid'));
        }

        $apiStmt = Database::getConnection()->prepare('SELECT API_gemini FROM ct_utenti WHERE id_utente = :id_utente LIMIT 1');
        $apiStmt->execute(['id_utente' => $teacherId]);
        $apiKey = trim((string) ($apiStmt->fetchColumn() ?: ''));
        if ($apiKey === '') {
            return $this->error($this->translator->translate('teacher.quest.gemini.api_key_missing'));
        }

        $deliveryText = $this->extractDeliveryTextForGemini($delivery);
        if ($deliveryText === '') {
            return $this->error($this->translator->translate('teacher.quest.gemini.no_text_content'));
        }

        $prompt = $this->translator->translate('teacher.quest.gemini.prompt')
            . "\n" . $this->translator->translate('teacher.quest.gemini.prompt.topic') . ': ' . (string) ($delivery['nome_argomento'] ?? '')
            . "\n" . $this->translator->translate('teacher.quest.gemini.prompt.exercise_text') . ': ' . trim(strip_tags(html_entity_decode((string) ($delivery['testo_esercizio'] ?? ''))))
            . "\n" . $this->translator->translate('teacher.quest.gemini.prompt.delivery') . ":\n" . $deliveryText;
        
       
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . urlencode($apiKey);
        $payload = ['contents' => [[ 'parts' => [[ 'text' => $prompt ]] ]]];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $response = curl_exec($ch);
        $httpStatus = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return $this->error(sprintf($this->translator->translate('teacher.quest.gemini.call_error'), $error));
        }
        curl_close($ch);

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            return $this->error(sprintf($this->translator->translate('teacher.quest.gemini.call_error'), 'Risposta non valida: ' . substr($response, 0, 300)));
        }

        if (isset($decoded['error']) && is_array($decoded['error'])) {
            $errorMessage = (string) ($decoded['error']['message'] ?? $this->translator->translate('teacher.quest.gemini.empty_response'));
            return $this->error(sprintf($this->translator->translate('teacher.quest.gemini.call_error'), $errorMessage));
        }

        if ($httpStatus >= 400) {
            return $this->error(sprintf($this->translator->translate('teacher.quest.gemini.call_error'), 'HTTP ' . $httpStatus . ': ' . substr($response, 0, 300)));
        }

        $textParts = [];
        foreach ((array) ($decoded['candidates'][0]['content']['parts'] ?? []) as $part) {
            if (isset($part['text'])) {
                $textParts[] = (string) $part['text'];
            }
        }

        $text = trim(implode("\n", $textParts));
        if ($text === '') {
            $finishReason = (string) ($decoded['candidates'][0]['finishReason'] ?? '');
            $blockReason = (string) ($decoded['promptFeedback']['blockReason'] ?? '');
            $details = trim($finishReason !== '' ? 'finishReason: ' . $finishReason : '');
            if ($blockReason !== '') {
                $details .= ($details !== '' ? ', ' : '') . 'blockReason: ' . $blockReason;
            }

            return $this->error($this->translator->translate('teacher.quest.gemini.empty_response') . ($details !== '' ? ' (' . $details . ')' : ''));
        }

        if (!preg_match('/\b(10|[1-9])\b/', $text, $match)) {
            return $this->error($this->translator->translate('teacher.quest.gemini.score_parse_failed'));
        }
        
        return [
            'success' => true,
            'valutazione' => (int) $match[1],
            'commento' => trim((string) preg_replace('/^\s*(10|[1-9])\s*,?\s*/', '', $text)),
        ];
    }

    public function getImportExportMenuPageData(): array
    {
        $data = $this->getQuestPageData();
        $data['originalQuestsInClass'] = $this->getOriginalQuestsForClass((int) (new PermissionService())->getCurrentClassId());
        return $data;
    }

    public function getExternalOriginalQuestsPageData(): array
    {
        $data = $this->getQuestPageData();
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            $data['availableExternalOriginalQuests'] = [];
            return $data;
        }

        $classId = (int) ((new PermissionService())->getCurrentClassId() ?? 0);
        $data['availableExternalOriginalQuests'] = $this->getImportableOriginalQuestsFromOtherClasses($classId);
        return $data;
    }

    public function getExternalOriginalQuestExercisesPageData(int $questId): array
    {
        $data = $this->getExternalOriginalQuestsPageData();
        $data['quest'] = null;
        $data['externalExercises'] = [];
        if (($data['permissionStatus'] ?? null) !== PermissionService::STATUS_OK || $questId <= 0) {
            return $data;
        }

        foreach (($data['availableExternalOriginalQuests'] ?? []) as $quest) {
            if ((int) ($quest['id_quest'] ?? 0) === $questId) {
                $data['quest'] = $quest;
                break;
            }
        }
        if (($data['quest'] ?? null) === null) {
            return $data;
        }

        $data['externalExercises'] = $this->getQuestExercisesOverview($questId);
        return $data;
    }

    public function importOriginalQuestFromAnotherClass(int $questId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }
        if ($questId <= 0) {
            return $this->error($this->translator->translate('teacher.quest.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $sourceQuest = $this->findImportableOriginalQuestById($classId, $questId);
        if ($sourceQuest === null) {
            return $this->error($this->translator->translate('teacher.quest.not_importable'));
        }

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $newQuestId = $this->duplicateQuestTree($pdo, $questId, $classId);
            $pdo->commit();
            return ['success' => true, 'message' => $this->translator->translate('teacher.quest.import.success'), 'newQuestId' => $newQuestId];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $this->error($exception->getMessage());
        }
    }

    public function buildQuestExportArchive(int $questId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $quest = $this->findQuestById($classId, $questId);
        if ($quest === null) {
            return $this->error($this->translator->translate('teacher.quest.not_found'));
        }

        $this->ensureQuestTreeUuids($questId);
        $payload = $this->buildQuestExportPayload($questId, $classId);

        $tmpDir = sys_get_temp_dir() . '/chronoquest_export_' . uniqid('', true);
        $assetsDir = $tmpDir . '/assets';
        if (!mkdir($assetsDir, 0775, true) && !is_dir($assetsDir)) {
            return $this->error($this->translator->translate('teacher.quest.export.temp_dir_failed'));
        }

        file_put_contents($tmpDir . '/quest.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $copiedFiles = $this->copyQuestAssetsForExport($payload, $assetsDir);
        $copiedFiles = array_merge($copiedFiles, $this->copyQuestEditorImageDirectoryForExport($questId, (string) ($payload['quest']['uuid'] ?? ''), $assetsDir));

        $zipPath = sys_get_temp_dir() . '/chronoquest_' . preg_replace('/[^a-z0-9_-]+/i', '_', (string) ($quest['nome_quest'] ?? 'quest')) . '_' . date('Ymd_His') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return $this->error($this->translator->translate('teacher.quest.export.zip_create_failed'));
        }

        $zip->addFile($tmpDir . '/quest.json', 'quest.json');
        foreach ($copiedFiles as $relative => $absolute) {
            $zip->addFile($absolute, 'assets/' . $relative);
        }
        $zip->close();
        $this->deleteDirectory($tmpDir);

        return ['success' => true, 'absolutePath' => $zipPath, 'fileName' => basename($zipPath)];
    }

    public function importQuestFromArchive(array $files, array $topicResolution = []): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $archive = is_array($files['quest_archive'] ?? null) ? $files['quest_archive'] : [];
        if (($archive['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->translator->translate('teacher.quest.import.invalid_archive'));
        }
        $tmpName = (string) ($archive['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->translator->translate('teacher.quest.import.invalid_zip_file'));
        }

        $extractDir = sys_get_temp_dir() . '/chronoquest_import_' . uniqid('', true);
        if (!mkdir($extractDir, 0775, true) && !is_dir($extractDir)) {
            return $this->error($this->translator->translate('teacher.quest.import.prepare_failed'));
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.quest.import.open_zip_failed'));
        }
        $zip->extractTo($extractDir);
        $zip->close();

        $jsonPath = $extractDir . '/quest.json';
        if (!is_file($jsonPath)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.quest.import.quest_json_missing'));
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        if (!is_array($payload)) {
            $this->deleteDirectory($extractDir);
            return $this->error($this->translator->translate('teacher.quest.import.invalid_json'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $newQuestId = $this->importQuestPayload($pdo, $payload, $extractDir, $classId, $topicResolution);
            $pdo->commit();
            $this->deleteDirectory($extractDir);
            return ['success' => true, 'message' => $this->translator->translate('teacher.quest.import.file.success'), 'newQuestId' => $newQuestId];
        } catch (\RuntimeException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deleteDirectory($extractDir);
            $resolutionPayload = json_decode($exception->getMessage(), true);
            if (is_array($resolutionPayload)) {
                return $resolutionPayload;
            }
            return $this->error($exception->getMessage());
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deleteDirectory($extractDir);
            return $this->error($exception->getMessage());
        }
    }

    private function getClassroom(int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function getQuestsForClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.id_quest,
                    q.uuid,
                    q.nome_quest,
                    q.image_quest,
                    q.piantina_quest,
                    COALESCE(q.blocca_ese, 1) AS blocca_ese
             FROM ct_classi_quest cq
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             WHERE cq.fk_classe = :fk_classe
             ORDER BY q.nome_quest ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn (array $quest): array => $this->normalizeQuestPaths($quest), $rows);
    }

    private function findQuestById(int $classId, int $questId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.id_quest, q.uuid, q.nome_quest, q.image_quest, q.piantina_quest, COALESCE(q.blocca_ese, 1) AS blocca_ese
             FROM ct_classi_quest cq
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             WHERE cq.fk_classe = :fk_classe
               AND q.id_quest = :id_quest
             LIMIT 1'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'id_quest' => $questId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->normalizeQuestPaths($row);
    }



    private function normalizeQuestPaths(array $quest): array
    {
        $quest['image_quest'] = $this->normalizeQuestAssetPath((string) ($quest['image_quest'] ?? ''));
        $quest['piantina_quest'] = $this->normalizeQuestAssetPath((string) ($quest['piantina_quest'] ?? ''));

        return $quest;
    }

    private function normalizeQuestAssetPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $path) === 1 || str_starts_with($path, '/')) {
            return $path;
        }

        $normalized = ltrim($path, './');

        if (str_starts_with($normalized, 'legacy/')) {
            return '/' . $normalized;
        }

        if (str_starts_with($normalized, 'materiali_vari/')) {
            return '/legacy/' . $normalized;
        }

        if (str_starts_with($normalized, 'pages/')) {
            return '/legacy/' . $normalized;
        }

        return '/legacy/pages/Rewarding/' . $normalized;
    }

    private function getQuestChapters(int $questId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_capitolo, c.nome_capitolo, c.coord_x, c.coord_y, cq.progressivo
             FROM ct_capitoli_quest cq
             INNER JOIN ct_capitoli c ON c.id_capitolo = cq.fk_capitolo
             WHERE cq.fk_quest = :fk_quest
             ORDER BY cq.progressivo ASC, c.id_capitolo ASC'
        );
        $stmt->execute(['fk_quest' => $questId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getNextChapterProgressive(int $questId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COALESCE(MAX(progressivo), 0) + 1 AS next_progressive
             FROM ct_capitoli_quest
             WHERE fk_quest = :fk_quest'
        );
        $stmt->execute(['fk_quest' => $questId]);

        $next = (int) ($stmt->fetchColumn() ?: 1);

        return $next > 0 ? $next : 1;
    }

    private function findChapterInQuest(int $questId, int $chapterId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_capitolo, c.nome_capitolo, c.coord_x, c.coord_y, cq.progressivo
             FROM ct_capitoli_quest cq
             INNER JOIN ct_capitoli c ON c.id_capitolo = cq.fk_capitolo
             WHERE cq.fk_quest = :fk_quest
               AND c.id_capitolo = :id_capitolo
             LIMIT 1'
        );
        $stmt->execute([
            'fk_quest' => $questId,
            'id_capitolo' => $chapterId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function getChapterExercises(int $classId, int $questId, int $chapterId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT eq.progressivo,
                    e.id_esercizio,
                    e.nome_capitolo,
                    a.nome_argomento,
                    t.tipo,
                    (select tr.traduzione from ct_traduzioni tr where tr.nome_tabella = \'ct_tipi_esercizio\' and tr.nome_campo = \'tipo\' and tr.lingua = \'en\' and tr.fk_collegamento = t.id_tipo_esercizio) as tipo_en,
                    e.punti_esperienza,
                    COALESCE(cea.attivo, 0) AS attivo,
                    (
                        SELECT COUNT(*)
                        FROM ct_consegne_studenti cs
                        WHERE cs.fk_esercizio = e.id_esercizio
                          AND cs.valutato = 0
                    ) AS tot_mancanti
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_esercizi e ON e.id_esercizio = eq.fk_esercizio
             INNER JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
             INNER JOIN ct_tipi_esercizio t ON t.id_tipo_esercizio = e.tipo_esercizio
             LEFT JOIN ct_classi_esercizi_attivi cea
                ON cea.fk_capitolo = cq.fk_capitolo
               AND cea.fk_esercizio = e.id_esercizio
               AND cea.fk_classe = :fk_classe
             WHERE cq.fk_quest = :fk_quest
               AND cq.fk_capitolo = :fk_capitolo
             ORDER BY eq.progressivo ASC, e.id_esercizio ASC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_quest' => $questId,
            'fk_capitolo' => $chapterId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getExerciseTypes(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id_tipo_esercizio,
                    tipo,
                    (select tr.traduzione from ct_traduzioni tr where tr.nome_tabella = \'ct_tipi_esercizio\' and tr.nome_campo = \'tipo\' and tr.lingua = \'en\' and tr.fk_collegamento = ct_tipi_esercizio.id_tipo_esercizio) as tipo_en
             FROM ct_tipi_esercizio
             ORDER BY id_tipo_esercizio ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findExerciseInChapter(int $classId, int $questId, int $chapterId, int $exerciseId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT e.*, eq.progressivo, COALESCE(cea.attivo, 0) AS attivo
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_esercizi e ON e.id_esercizio = eq.fk_esercizio
             LEFT JOIN ct_classi_esercizi_attivi cea
                ON cea.fk_capitolo = cq.fk_capitolo
               AND cea.fk_esercizio = e.id_esercizio
               AND cea.fk_classe = :fk_classe
             WHERE cq.fk_quest = :fk_quest
               AND cq.fk_capitolo = :fk_capitolo
               AND e.id_esercizio = :id_esercizio
             LIMIT 1'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_quest' => $questId,
            'fk_capitolo' => $chapterId,
            'id_esercizio' => $exerciseId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getExerciseMaterialBindings(int $exerciseId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT fk_materiale, link
             FROM ct_esercizio_materiali
             WHERE fk_esercizio = :fk_esercizio'
        );
        $stmt->execute(['fk_esercizio' => $exerciseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getExerciseExportMaterialBindings(int $exerciseId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT em.fk_materiale, em.link, m.nome_materiale
             FROM ct_esercizio_materiali em
             LEFT JOIN ct_materiali m ON m.id_materiale = em.fk_materiale
             WHERE em.fk_esercizio = :fk_esercizio'
        );
        $stmt->execute(['fk_esercizio' => $exerciseId]);

        return array_map(static function (array $binding): array {
            return [
                'fk_materiale' => (int) ($binding['fk_materiale'] ?? 0),
                'nome_materiale' => (string) ($binding['nome_materiale'] ?? ''),
                'link' => (string) ($binding['link'] ?? ''),
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function findSubjectIdByTopic(int $topicId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT fk_materia
             FROM ct_argomenti
             WHERE id_argomento = :id_argomento
             LIMIT 1'
        );
        $stmt->execute(['id_argomento' => $topicId]);
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    private function getTeacherSubjects(int $userId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_materia, m.nome_materia
             FROM ct_materie m
             INNER JOIN ct_utenti_materie um ON um.fk_materia = m.id_materia
             WHERE um.fk_utente = :fk_utente
             ORDER BY m.nome_materia ASC'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getTopicsBySubject(int $subjectId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_argomento, nome_argomento
             FROM ct_argomenti
             WHERE fk_materia = :fk_materia
             ORDER BY nome_argomento ASC'
        );
        $stmt->execute(['fk_materia' => $subjectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getMaterialsByTopic(int $topicId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_materiale, nome_materiale
             FROM ct_materiali
             WHERE fk_argomento = :fk_argomento
             ORDER BY nome_materiale ASC'
        );
        $stmt->execute(['fk_argomento' => $topicId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function extractValidLinks(string $linksRaw): array
    {
        $links = [];
        $rows = preg_split('/\r\n|\r|\n/', $linksRaw) ?: [];
        foreach ($rows as $row) {
            $candidate = trim($row);
            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_URL) !== false) {
                $links[] = $candidate;
            }
        }

        return array_values(array_unique($links));
    }

    private function countQuestionsByTopicAndType(int $topicId, string $typeCondition): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) AS total
             FROM ct_domande
             WHERE fk_argomento = :fk_argomento
               AND ' . $typeCondition
        );
        $stmt->execute(['fk_argomento' => $topicId]);

        return (int) $stmt->fetchColumn();
    }

    private function getNextExerciseProgressive(int $chapterId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COALESCE(MAX(progressivo), 0) + 1
             FROM ct_esercizi_quest
             WHERE fk_capitolo = :fk_capitolo'
        );
        $stmt->execute(['fk_capitolo' => $chapterId]);

        $next = (int) $stmt->fetchColumn();

        return $next > 0 ? $next : 1;
    }

    private function resolveQuestImagePath(array $uploadedFile, ?string $currentPath): ?string
    {
        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $currentPath;
        }

        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.image.upload_error'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.image.file_invalid'));
        }

        $originalName = (string) ($uploadedFile['name'] ?? 'quest.png');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed, true)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.image.format_unsupported'));
        }

        $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Quest';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.image.folder_create_failed'));
        }

        $targetName = uniqid('quest_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $targetName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.image.save_failed'));
        }

        return '/assets/images/Quest/' . $targetName;
    }

    private function resolveMapImagePath(array $uploadedFile, ?string $currentPath): ?string
    {
        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $currentPath;
        }

        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.map.upload_error'));
        }

        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.map.file_invalid'));
        }

        $originalName = (string) ($uploadedFile['name'] ?? 'piantina.png');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed, true)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.map.format_unsupported'));
        }

        $targetDir = dirname(__DIR__, 2) . '/public/assets/images/Quest/Piantine';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.map.folder_create_failed'));
        }

        $targetName = uniqid('piantina_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $targetName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException($this->translator->translate('teacher.quest.map.save_failed'));
        }

        return '/assets/images/Quest/Piantine/' . $targetName;
    }

    private function findStudentDeliveriesForExercise(int $classId, int $exerciseId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, u.nome, u.cognome, cs.id_consegna,
                    COALESCE(cs.valutazione, 0) AS valutazione,
                    COALESCE(cs.valutato, 0) AS valutato
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             LEFT JOIN ct_consegne_studenti cs
                ON cs.fk_studente = s.id_studente
               AND cs.fk_esercizio = :fk_esercizio
             WHERE sc.fk_classe = :fk_classe
             ORDER BY u.cognome ASC, u.nome ASC'
        );
        $stmt->execute([
            'fk_esercizio' => $exerciseId,
            'fk_classe' => $classId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findUnevaluatedDeliveriesForQuest(int $classId, int $questId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT cs.id_consegna,
                    COALESCE(cs.problema, 0) AS problema,
                    COALESCE(cs.descrizione_problema, "") AS descrizione_problema,
                    s.id_studente,
                    u.nome,
                    u.cognome,
                    c.id_capitolo,
                    c.nome_capitolo AS nome_capitolo,
                    e.id_esercizio,
                    e.nome_capitolo AS nome_esercizio
             FROM ct_consegne_studenti cs
             INNER JOIN ct_studenti s ON s.id_studente = cs.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_esercizi e ON e.id_esercizio = cs.fk_esercizio
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
             INNER JOIN ct_capitoli c ON c.id_capitolo = eq.fk_capitolo
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE sc.fk_classe = :fk_classe
               AND cq.fk_quest = :fk_quest
               AND cs.valutato = 0
             ORDER BY u.cognome ASC, u.nome ASC, c.nome_capitolo ASC, e.nome_capitolo ASC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_quest' => $questId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findDeliveryInQuest(int $classId, int $questId, int $deliveryId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT cs.id_consegna,
                    s.id_studente,
                    c.id_capitolo,
                    c.nome_capitolo AS nome_capitolo,
                    e.id_esercizio,
                    e.nome_capitolo AS nome_esercizio
             FROM ct_consegne_studenti cs
             INNER JOIN ct_studenti s ON s.id_studente = cs.fk_studente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_esercizi e ON e.id_esercizio = cs.fk_esercizio
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
             INNER JOIN ct_capitoli c ON c.id_capitolo = eq.fk_capitolo
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE sc.fk_classe = :fk_classe
               AND cq.fk_quest = :fk_quest
               AND cs.valutato = 0
               AND cs.id_consegna = :id_consegna
             LIMIT 1'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_quest' => $questId,
            'id_consegna' => $deliveryId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function findStudentDeliveryInExercise(int $classId, int $exerciseId, int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.nome_quest, c.id_capitolo, c.nome_capitolo, e.id_esercizio, e.nome_capitolo,
                    e.testo_esercizio, e.testo_ese104, e.tipo_esercizio, e.punti_esperienza, e.monete_guadagnate,e.livello_diff,
                    a.nome_argomento, s.id_studente, s.l104, u.nome, u.cognome, cs.id_consegna, cs.file_consegnato,
                    COALESCE(cs.valutazione, 0) AS valutazione, COALESCE(cs.valutato, 0) AS valutato,
                    er.testo_risposta AS risposta_aperta, er.commento_prof AS commento_prof
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_esercizi e ON e.id_esercizio = :id_esercizio
             INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
             INNER JOIN ct_capitoli c ON c.id_capitolo = eq.fk_capitolo
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             INNER JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
             LEFT JOIN ct_consegne_studenti cs
                ON cs.fk_studente = s.id_studente
               AND cs.fk_esercizio = e.id_esercizio
             LEFT JOIN ct_esercizio_risposte er
                ON er.fk_consegna = cs.id_consegna
               AND er.fk_esercizio = e.id_esercizio
               AND er.fk_studente = s.id_studente
               AND er.fk_domanda IS NULL
             WHERE sc.fk_classe = :fk_classe
               AND s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute([
            'id_esercizio' => $exerciseId,
            'fk_classe' => $classId,
            'id_studente' => $studentId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getStudentOpenQuizAnswers(int $exerciseId, int $studentId, int $deliveryId): array
    {
        if ($deliveryId <= 0) {
            return [];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT d.id_domanda, d.domanda, er.testo_risposta, er.commento_prof
             FROM ct_esercizio_domande ed
             INNER JOIN ct_domande d ON d.id_domanda = ed.fk_domanda
             INNER JOIN ct_esercizio_risposte er
                ON er.fk_domanda = d.id_domanda
               AND er.fk_esercizio = ed.fk_esercizio
               AND er.fk_studente = ed.fk_studente
             WHERE ed.fk_esercizio = :fk_esercizio
               AND ed.fk_studente = :fk_studente
               AND d.fk_tipo_domanda = 1
               AND er.fk_consegna = :fk_consegna
             ORDER BY d.id_domanda ASC'
        );
        $stmt->execute([
            'fk_esercizio' => $exerciseId,
            'fk_studente' => $studentId,
            'fk_consegna' => $deliveryId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getStudentMultipleChoiceQuizAnswers(int $exerciseId, int $studentId, int $deliveryId): array
    {
        if ($deliveryId <= 0) {
            return [];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT d.id_domanda,
                    d.domanda,
                    r.id_risposta,
                    r.risposta,
                    COALESCE(r.corretta, 0) AS corretta,
                    CASE WHEN er.fk_risposta = r.id_risposta THEN 1 ELSE 0 END AS selected_by_student
             FROM ct_esercizio_domande ed
             INNER JOIN ct_domande d ON d.id_domanda = ed.fk_domanda
             INNER JOIN ct_risposte r ON r.fk_domanda = d.id_domanda
             LEFT JOIN ct_esercizio_risposte er
                ON er.fk_domanda = d.id_domanda
               AND er.fk_esercizio = ed.fk_esercizio
               AND er.fk_studente = ed.fk_studente
               AND er.fk_consegna = :fk_consegna
             WHERE ed.fk_esercizio = :fk_esercizio
               AND ed.fk_studente = :fk_studente
               AND d.fk_tipo_domanda = 2
             ORDER BY d.id_domanda ASC, r.id_risposta ASC'
        );
        $stmt->execute([
            'fk_esercizio' => $exerciseId,
            'fk_studente' => $studentId,
            'fk_consegna' => $deliveryId,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        if ($rows === []) {
            return [];
        }

        $questions = [];
        foreach ($rows as $row) {
            $questionId = (int) ($row['id_domanda'] ?? 0);
            if ($questionId <= 0) {
                continue;
            }

            if (!isset($questions[$questionId])) {
                $questions[$questionId] = [
                    'id_domanda' => $questionId,
                    'domanda' => (string) ($row['domanda'] ?? ''),
                    'selected_risposta_id' => null,
                    'is_correct' => false,
                    'answers' => [],
                ];
            }

            $answerId = (int) ($row['id_risposta'] ?? 0);
            $isSelected = (int) ($row['selected_by_student'] ?? 0) === 1;
            $isCorrect = (int) ($row['corretta'] ?? 0) === 1;
            if ($isSelected) {
                $questions[$questionId]['selected_risposta_id'] = $answerId;
                $questions[$questionId]['is_correct'] = $isCorrect;
            }

            $questions[$questionId]['answers'][] = [
                'id_risposta' => $answerId,
                'risposta' => (string) ($row['risposta'] ?? ''),
                'corretta' => $isCorrect ? 1 : 0,
                'selected_by_student' => $isSelected ? 1 : 0,
            ];
        }

        return array_values($questions);
    }

    private function buildMultipleChoiceScore(array $questions, int $totalQuestions): array
    {
        $availableQuestions = count($questions);
        $denominator = $totalQuestions > 0 ? $totalQuestions : $availableQuestions;
        if ($denominator <= 0) {
            $denominator = 1;
        }

        $correctAnswers = 0;
        foreach ($questions as $question) {
            if ((bool) ($question['is_correct'] ?? false)) {
                $correctAnswers++;
            }
        }

        $grade = (int) ceil(($correctAnswers / $denominator) * 10);
        if ($grade < 1) {
            $grade = 1;
        }
        if ($grade > 10) {
            $grade = 10;
        }

        return [
            'correct_answers' => $correctAnswers,
            'total_questions' => $denominator,
            'grade' => $grade,
        ];
    }

    private function buildSubmittedFiles(string $rawPath): array
    {
        $path = trim($rawPath);
        $publicRoot = dirname(__DIR__, 2) . '/public';

        if ($path === '') {
            return [];
        }

        if (str_contains($path, '..')) {
            $path = ltrim($path, '.');
        }

        if (is_file($path)) {
            return [[
                'name' => basename($path),
                'path' => $path,
                'absolute_path' => $path,
            ]];
        }

        $path_cartella = $publicRoot . '/' . ltrim($path, '/');

        if (!is_dir($path_cartella)) {
            return [];
        }

        $files = [];

        foreach (array_diff(scandir($path_cartella) ?: [], ['.', '..']) as $entry) {
            $absolutePath = $path_cartella . '/' . $entry;
            if (!is_file($absolutePath)) {
                continue;
            }

            $filePath = rtrim($path, '/') . '/' . $entry;
            $files[] = [
                'name' => $entry,
                'path' => $filePath,
                'absolute_path' => $absolutePath,
            ];
            
        }

        return $files;
    }

    private function extractDeliveryTextForGemini(array $delivery): string
    {
        $exerciseType = (int) ($delivery['tipo_esercizio'] ?? 0);
        if ($exerciseType === 1) {
            return trim(strip_tags(html_entity_decode((string) ($delivery['risposta_aperta'] ?? ''))));
        }

        if ($exerciseType === 4) {
            $answers = $this->getStudentOpenQuizAnswers(
                (int) ($delivery['id_esercizio'] ?? 0),
                (int) ($delivery['id_studente'] ?? 0),
                (int) ($delivery['id_consegna'] ?? 0)
            );
            $parts = [];
            foreach ($answers as $answer) {
                $parts[] = 'Domanda: ' . trim(strip_tags(html_entity_decode((string) ($answer['domanda'] ?? ''))))
                    . "\nRisposta: " . trim(strip_tags(html_entity_decode((string) ($answer['testo_risposta'] ?? ''))));
            }
            return implode("\n\n", $parts);
        }

        if ($exerciseType === 2) {
            $answers = $this->getStudentMultipleChoiceQuizAnswers(
                (int) ($delivery['id_esercizio'] ?? 0),
                (int) ($delivery['id_studente'] ?? 0),
                (int) ($delivery['id_consegna'] ?? 0)
            );
            $parts = [];
            foreach ($answers as $question) {
                $questionText = trim(strip_tags(html_entity_decode((string) ($question['domanda'] ?? ''))));
                $selectedAnswer = '';
                foreach (($question['answers'] ?? []) as $answer) {
                    if ((int) ($answer['selected_by_student'] ?? 0) === 1) {
                        $selectedAnswer = trim(strip_tags(html_entity_decode((string) ($answer['risposta'] ?? ''))));
                        break;
                    }
                }

                if ($selectedAnswer === '') {
                    $selectedAnswer = '[nessuna risposta selezionata]';
                }

                $parts[] = 'Domanda: ' . $questionText . "\nRisposta selezionata: " . $selectedAnswer;
            }

            return implode("\n\n", $parts);
        }

        if ($exerciseType === 3) {
            
            $allowed = ['txt', 'py', 'php', 'html', 'css', 'js', 'java', 'c', 'cpp', 'md', 'json', 'xml'];
            $chunks = [];
            foreach ($this->buildSubmittedFiles((string) ($delivery['file_consegnato'] ?? '')) as $file) {
                $path = (string) ($file['path'] ?? '');
                $absolutePath = (string) ($file['absolute_path'] ?? $path);
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) {
                    continue;
                }
                $content = @file_get_contents($absolutePath);
                if ($content === false) {
                    continue;
                }
                $chunks[] = 'File: ' . (string) ($file['name'] ?? basename($path)) . "\n" . $content;
            }
            return implode("\n\n", $chunks);
        }

        return '';
    }

    private function insertStudentAlert(int $classId, int $studentId, string $text, string $type, string $link): void
    {
        Database::getConnection()->prepare(
            'INSERT INTO ct_alerts (fk_classe, testo, fk_studente, data_alert, tipologia, link, letto, doc_stud)
             VALUES (:fk_classe, :testo, :fk_studente, :data_alert, :tipologia, :link, 0, 1)'
        )->execute([
            'fk_classe' => $classId,
            'testo' => $text,
            'fk_studente' => $studentId,
            'data_alert' => date('Y-m-d H:i:s'),
            'tipologia' => $type,
            'link' => $link,
        ]);
    }

    private function getClassStudentIds(int $classId): array
    {
        if ($classId <= 0) {
            return [];
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT sc.fk_studente
             FROM ct_studenti_classi sc
             WHERE sc.fk_classe = :fk_classe'
        );
        $stmt->execute(['fk_classe' => $classId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return array_map(static fn (array $row): int => (int) ($row['fk_studente'] ?? 0), $rows);
    }

    private function getOriginalQuestsForClass(int $classId): array
    {
        if ($classId <= 0) {
            return [];
        }
        $stmt = Database::getConnection()->prepare(
            'SELECT q.id_quest, q.nome_quest, q.image_quest, q.uuid
             FROM ct_classi_quest cq
             INNER JOIN ct_quest q ON q.id_quest = cq.fk_quest
             WHERE cq.fk_classe = :fk_classe
               AND COALESCE(q.originale, 1) = 1
             ORDER BY q.nome_quest ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);
        return array_map(fn (array $quest): array => $this->normalizeQuestPaths($quest), $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function getImportableOriginalQuestsFromOtherClasses(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT q.id_quest, q.nome_quest, q.image_quest, q.uuid
             FROM ct_quest q
             WHERE COALESCE(q.originale, 0) = 1
               AND q.id_quest NOT IN (SELECT fk_quest FROM ct_classi_quest WHERE fk_classe = :fk_classe)
             ORDER BY q.nome_quest ASC'
        );
        $stmt->execute(['fk_classe' => $classId]);
        return array_map(fn (array $quest): array => $this->normalizeQuestPaths($quest), $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function getQuestExercisesOverview(int $questId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_capitolo, c.nome_capitolo, a.nome_argomento, eq.progressivo, e.id_esercizio, e.nome_capitolo AS nome_esercizio, t.tipo,
                    (select tr.traduzione from ct_traduzioni tr where tr.nome_tabella = \'ct_tipi_esercizio\' and tr.nome_campo = \'tipo\' and tr.lingua = \'en\' and tr.fk_collegamento = t.id_tipo_esercizio) as tipo_en,
                    e.punti_esperienza
             FROM ct_capitoli_quest cq
             INNER JOIN ct_esercizi_quest eq ON eq.fk_capitolo = cq.fk_capitolo
             INNER JOIN ct_esercizi e ON e.id_esercizio = eq.fk_esercizio
             INNER JOIN ct_capitoli c ON c.id_capitolo = cq.fk_capitolo
             INNER JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
             INNER JOIN ct_tipi_esercizio t ON t.id_tipo_esercizio = e.tipo_esercizio
             WHERE cq.fk_quest = :fk_quest
             ORDER BY cq.progressivo ASC, eq.progressivo ASC'
        );
        $stmt->execute(['fk_quest' => $questId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function findImportableOriginalQuestById(int $classId, int $questId): ?array
    {
        foreach ($this->getImportableOriginalQuestsFromOtherClasses($classId) as $quest) {
            if ((int) ($quest['id_quest'] ?? 0) === $questId) {
                return $quest;
            }
        }
        return null;
    }

    private function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function duplicateQuestTree(PDO $pdo, int $sourceQuestId, int $targetClassId): int
    {
        $source = $pdo->prepare('SELECT * FROM ct_quest WHERE id_quest = :id_quest');
        $source->execute(['id_quest' => $sourceQuestId]);
        $quest = $source->fetch(PDO::FETCH_ASSOC);
        if ($quest === false) {
            throw new \RuntimeException($this->translator->translate('quest.notfound'));
        }
        $pdo->prepare('INSERT INTO ct_quest (uuid, nome_quest, image_quest, piantina_quest, blocca_ese, originale) VALUES (:uuid, :nome_quest, :image_quest, :piantina_quest, :blocca_ese, 0)')
            ->execute([
                'uuid' => $this->generateUuidV4(),
                'nome_quest' => (string) ($quest['nome_quest'] ?? ''),
                'image_quest' => (string) ($quest['image_quest'] ?? ''),
                'piantina_quest' => (string) ($quest['piantina_quest'] ?? ''),
                'blocca_ese' => (int) ($quest['blocca_ese'] ?? 1),
            ]);
        $newQuestId = (int) $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO ct_classi_quest (fk_quest, fk_classe) VALUES (:fk_quest, :fk_classe)')
            ->execute(['fk_quest' => $newQuestId, 'fk_classe' => $targetClassId]);

        $chaptersStmt = $pdo->prepare(
            'SELECT c.*, cq.progressivo
             FROM ct_capitoli c
             INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE cq.fk_quest = :fk_quest
             ORDER BY cq.progressivo ASC'
        );
        $chaptersStmt->execute(['fk_quest' => $sourceQuestId]);
        foreach ($chaptersStmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $chapter) {
            $pdo->prepare('INSERT INTO ct_capitoli (uuid, nome_capitolo, coord_x, coord_y) VALUES (:uuid, :nome_capitolo, :coord_x, :coord_y)')
                ->execute([
                    'uuid' => $this->generateUuidV4(),
                    'nome_capitolo' => (string) ($chapter['nome_capitolo'] ?? ''),
                    'coord_x' => (int) ($chapter['coord_x'] ?? 0),
                    'coord_y' => (int) ($chapter['coord_y'] ?? 0),
                ]);
            $newChapterId = (int) $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO ct_capitoli_quest (fk_quest, fk_capitolo, progressivo) VALUES (:fk_quest, :fk_capitolo, :progressivo)')
                ->execute(['fk_quest' => $newQuestId, 'fk_capitolo' => $newChapterId, 'progressivo' => (int) ($chapter['progressivo'] ?? 1)]);

            $exerciseStmt = $pdo->prepare(
                'SELECT e.*, eq.progressivo
                 FROM ct_esercizi e
                 INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
                 WHERE eq.fk_capitolo = :fk_capitolo
                 ORDER BY eq.progressivo ASC'
            );
            $exerciseStmt->execute(['fk_capitolo' => (int) ($chapter['id_capitolo'] ?? 0)]);
            foreach ($exerciseStmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $exercise) {
                $pdo->prepare(
                    'INSERT INTO ct_esercizi (uuid, testo_esercizio, testo_ese104, punti_esperienza, storia_esercizio, fk_argomento, tipo_esercizio, nome_capitolo, num_domande, monete_guadagnate, fk_materiale, livello_diff)
                     VALUES (:uuid, :testo_esercizio, :testo_ese104, :punti_esperienza, :storia_esercizio, :fk_argomento, :tipo_esercizio, :nome_capitolo, :num_domande, :monete_guadagnate, :fk_materiale, :livello_diff)'
                )->execute([
                    'uuid' => $this->generateUuidV4(),
                    'testo_esercizio' => (string) ($exercise['testo_esercizio'] ?? ''),
                    'testo_ese104' => (string) ($exercise['testo_ese104'] ?? ''),
                    'punti_esperienza' => (int) ($exercise['punti_esperienza'] ?? 0),
                    'storia_esercizio' => (string) ($exercise['storia_esercizio'] ?? ''),
                    'fk_argomento' => (int) ($exercise['fk_argomento'] ?? 0),
                    'tipo_esercizio' => (int) ($exercise['tipo_esercizio'] ?? 1),
                    'nome_capitolo' => (string) ($exercise['nome_capitolo'] ?? ''),
                    'num_domande' => (int) ($exercise['num_domande'] ?? 0),
                    'monete_guadagnate' => (int) ($exercise['monete_guadagnate'] ?? 0),
                    'fk_materiale' => (int) ($exercise['fk_materiale'] ?? 0),
                    'livello_diff' => (int) ($exercise['livello_diff'] ?? 1),
                ]);
                $newExerciseId = (int) $pdo->lastInsertId();
                $pdo->prepare('INSERT INTO ct_esercizi_quest (fk_capitolo, fk_esercizio, progressivo) VALUES (:fk_capitolo, :fk_esercizio, :progressivo)')
                    ->execute(['fk_capitolo' => $newChapterId, 'fk_esercizio' => $newExerciseId, 'progressivo' => (int) ($exercise['progressivo'] ?? 1)]);
                $pdo->prepare('INSERT INTO ct_classi_esercizi_attivi (fk_classe, fk_capitolo, fk_esercizio, attivo) VALUES (:fk_classe, :fk_capitolo, :fk_esercizio, 0)')
                    ->execute(['fk_classe' => $targetClassId, 'fk_capitolo' => $newChapterId, 'fk_esercizio' => $newExerciseId]);
            }
        }
        return $newQuestId;
    }

    private function ensureQuestTreeUuids(int $questId): void
    {
        $pdo = Database::getConnection();
        $this->ensureQuestUuid($questId);
        $chapters = $pdo->prepare('SELECT c.id_capitolo FROM ct_capitoli c INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo WHERE cq.fk_quest = :fk_quest AND (c.uuid IS NULL OR c.uuid = "")');
        $chapters->execute(['fk_quest' => $questId]);
        foreach ($chapters->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
            $pdo->prepare('UPDATE ct_capitoli SET uuid = :uuid WHERE id_capitolo = :id_capitolo')->execute(['uuid' => $this->generateUuidV4(), 'id_capitolo' => (int) $row['id_capitolo']]);
        }
        $exercises = $pdo->prepare('SELECT e.id_esercizio FROM ct_esercizi e INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = eq.fk_capitolo WHERE cq.fk_quest = :fk_quest AND (e.uuid IS NULL OR e.uuid = "")');
        $exercises->execute(['fk_quest' => $questId]);
        foreach ($exercises->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
            $pdo->prepare('UPDATE ct_esercizi SET uuid = :uuid WHERE id_esercizio = :id_esercizio')->execute(['uuid' => $this->generateUuidV4(), 'id_esercizio' => (int) $row['id_esercizio']]);
        }
    }

    private function ensureQuestUuid(int $questId): string
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT uuid FROM ct_quest WHERE id_quest = :id_quest LIMIT 1');
        $stmt->execute(['id_quest' => $questId]);
        $uuid = trim((string) ($stmt->fetchColumn() ?: ''));
        if ($uuid !== '') {
            return $uuid;
        }

        $uuid = $this->generateUuidV4();
        $pdo->prepare('UPDATE ct_quest SET uuid = :uuid WHERE id_quest = :id_quest')
            ->execute(['uuid' => $uuid, 'id_quest' => $questId]);

        return $uuid;
    }

    private function buildQuestExportPayload(int $questId, int $classId): array
    {
        $questStmt = Database::getConnection()->prepare('SELECT id_quest, uuid, nome_quest, image_quest, piantina_quest, blocca_ese FROM ct_quest WHERE id_quest = :id_quest');
        $questStmt->execute(['id_quest' => $questId]);
        $quest = $questStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $chaptersStmt = Database::getConnection()->prepare(
            'SELECT c.id_capitolo, c.uuid, c.nome_capitolo, c.coord_x, c.coord_y, cq.progressivo
             FROM ct_capitoli c INNER JOIN ct_capitoli_quest cq ON cq.fk_capitolo = c.id_capitolo
             WHERE cq.fk_quest = :fk_quest ORDER BY cq.progressivo ASC'
        );
        $chaptersStmt->execute(['fk_quest' => $questId]);
        $chapters = $chaptersStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($chapters as &$chapter) {
            $exStmt = Database::getConnection()->prepare(
                'SELECT e.*, eq.progressivo, a.uuid AS argomento_uuid, a.nome_argomento, m.nome_materiale AS materiale_nome
                 FROM ct_esercizi e INNER JOIN ct_esercizi_quest eq ON eq.fk_esercizio = e.id_esercizio
                 LEFT JOIN ct_argomenti a ON a.id_argomento = e.fk_argomento
                 LEFT JOIN ct_materiali m ON m.id_materiale = e.fk_materiale
                 WHERE eq.fk_capitolo = :fk_capitolo ORDER BY eq.progressivo ASC'
            );
            $exStmt->execute(['fk_capitolo' => (int) $chapter['id_capitolo']]);
            $chapterExercises = $exStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $chapter['exercises'] = [];
            foreach ($chapterExercises as $exercise) {
                $exercise['argomento'] = [
                    'uuid' => (string) ($exercise['argomento_uuid'] ?? ''),
                    'nome' => (string) ($exercise['nome_argomento'] ?? ''),
                ];
                $exercise['materiale'] = [
                    'nome' => (string) ($exercise['materiale_nome'] ?? ''),
                ];
                $exercise['materiali_collegati'] = $this->getExerciseExportMaterialBindings((int) ($exercise['id_esercizio'] ?? 0));
                unset($exercise['argomento_uuid'], $exercise['nome_argomento'], $exercise['fk_argomento'], $exercise['materiale_nome']);
                $chapter['exercises'][] = $exercise;
            }
        }
        unset($chapter);

        return ['exported_at' => date('c'), 'class_id' => $classId, 'quest' => $quest, 'chapters' => $chapters];
    }

    private function copyQuestAssetsForExport(array $payload, string $assetsDir): array
    {
        $copied = [];
        $paths = [
            (string) ($payload['quest']['image_quest'] ?? ''),
            (string) ($payload['quest']['piantina_quest'] ?? ''),
        ];
        foreach (($payload['chapters'] ?? []) as $chapter) {
            foreach (($chapter['exercises'] ?? []) as $exercise) {
                foreach (['testo_esercizio', 'testo_ese104', 'storia_esercizio'] as $field) {
                    $html = html_entity_decode((string) ($exercise[$field] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);
                    foreach (($matches[1] ?? []) as $src) {
                        $paths[] = $src;
                    }
                }
            }
        }

        foreach (array_unique($paths) as $path) {
            $absolute = $this->resolveAbsoluteAssetPath((string) $path);
            if ($absolute === null || !is_file($absolute)) {
                continue;
            }
            $name = $this->getArchiveAssetRelativePath((string) $path);
            if ($name === '') {
                continue;
            }
            $target = $assetsDir . '/' . $name;
            $targetDir = dirname($target);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                continue;
            }
            copy($absolute, $target);
            $copied[$name] = $target;
        }
        return $copied;
    }

    private function copyQuestEditorImageDirectoryForExport(int $questId, string $questUuid, string $assetsDir): array
    {
        $copied = [];
        $exportFolderName = $this->getQuestEditorImageFolderName($questId, $questUuid);
        $sourceDirs = array_unique([
            $this->getQuestEditorImageDirectory($questId, $questUuid),
            $this->getLegacyQuestEditorImageDirectory($questId),
        ]);

        foreach ($sourceDirs as $sourceDir) {
            if (!is_dir($sourceDir)) {
                continue;
            }

            $baseRelative = 'images/Quest/Editor/' . $exportFolderName;
            foreach (array_diff(scandir($sourceDir) ?: [], ['.', '..']) as $fileName) {
                $source = $sourceDir . '/' . $fileName;
                if (!is_file($source)) {
                    continue;
                }
                $safeName = basename($fileName);
                $relative = $baseRelative . '/' . $safeName;
                $target = $assetsDir . '/' . $relative;
                $targetDir = dirname($target);
                if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                    continue;
                }
                copy($source, $target);
                $copied[$relative] = $target;
            }
        }

        return $copied;
    }

    private function importQuestPayload(PDO $pdo, array $payload, string $extractDir, int $classId, array $topicResolution = []): int
    {
        $quest = (array) ($payload['quest'] ?? []);
        $newQuestUuid = trim((string) ($quest['uuid'] ?? ''));
        if ($newQuestUuid === '' || $this->questUuidExists($pdo, $newQuestUuid)) {
            $newQuestUuid = $this->generateUuidV4();
        }
        $pdo->prepare('INSERT INTO ct_quest (uuid, nome_quest, image_quest, piantina_quest, blocca_ese, originale) VALUES (:uuid, :nome_quest, :image_quest, :piantina_quest, :blocca_ese, 1)')
            ->execute([
                'uuid' => $newQuestUuid,
                'nome_quest' => (string) ($quest['nome_quest'] ?? $this->translator->translate('teacher.quest.import.default_name')),
                'image_quest' => $this->importAssetPath((string) ($quest['image_quest'] ?? ''), $extractDir),
                'piantina_quest' => $this->importAssetPath((string) ($quest['piantina_quest'] ?? ''), $extractDir),
                'blocca_ese' => (int) ($quest['blocca_ese'] ?? 1),
            ]);
        $newQuestId = (int) $pdo->lastInsertId();
        $this->ensureQuestEditorImageDirectory($newQuestId, $newQuestUuid);
        $pdo->prepare('INSERT INTO ct_classi_quest (fk_quest, fk_classe) VALUES (:fk_quest, :fk_classe)')
            ->execute(['fk_quest' => $newQuestId, 'fk_classe' => $classId]);

        $missingTopics = [];
        $chapters = (array) ($payload['chapters'] ?? []);
        foreach ($chapters as &$chapter) {
            $chapterExercises = (array) ($chapter['exercises'] ?? []);
            foreach ($chapterExercises as &$exercise) {
                $exercise['resolved_fk_argomento'] = $this->resolveTopicIdForImportedExercise($pdo, $exercise, $topicResolution, $missingTopics);
            }
            unset($exercise);
            $chapter['exercises'] = $chapterExercises;
        }
        unset($chapter);

        if ($missingTopics !== []) {
            $availableTopics = $pdo->query('SELECT a.id_argomento, a.nome_argomento, a.uuid, a.fk_materia, m.nome_materia FROM ct_argomenti a INNER JOIN ct_materie m ON m.id_materia = a.fk_materia ORDER BY m.nome_materia ASC, a.nome_argomento ASC')->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $availableSubjects = $pdo->query('SELECT id_materia, nome_materia FROM ct_materie ORDER BY nome_materia ASC')->fetchAll(PDO::FETCH_ASSOC) ?: [];
            throw new \RuntimeException(json_encode([
                'success' => false,
                'requires_topic_resolution' => true,
                'message' => $this->translator->translate('teacher.quest.import.missing_topics.prompt'),
                'missing_topics' => array_values($missingTopics),
                'available_topics' => $availableTopics,
                'available_subjects' => $availableSubjects,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE));
        }

        foreach ($chapters as $chapter) {
            $chapterUuid = trim((string) ($chapter['uuid'] ?? ''));
            if ($chapterUuid === '' || $this->recordUuidExists($pdo, 'ct_capitoli', $chapterUuid)) {
                $chapterUuid = $this->generateUuidV4();
            }
            $pdo->prepare('INSERT INTO ct_capitoli (uuid, nome_capitolo, coord_x, coord_y) VALUES (:uuid, :nome_capitolo, :coord_x, :coord_y)')
                ->execute([
                    'uuid' => $chapterUuid,
                    'nome_capitolo' => (string) ($chapter['nome_capitolo'] ?? ''),
                    'coord_x' => (int) ($chapter['coord_x'] ?? 0),
                    'coord_y' => (int) ($chapter['coord_y'] ?? 0),
                ]);
            $newChapterId = (int) $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO ct_capitoli_quest (fk_quest, fk_capitolo, progressivo) VALUES (:fk_quest, :fk_capitolo, :progressivo)')
                ->execute(['fk_quest' => $newQuestId, 'fk_capitolo' => $newChapterId, 'progressivo' => (int) ($chapter['progressivo'] ?? 1)]);

            foreach ((array) ($chapter['exercises'] ?? []) as $exercise) {
                $resolvedTopicId = (int) ($exercise['resolved_fk_argomento'] ?? 0);
                $resolvedMaterialId = $this->resolveMaterialIdForImportedExercise($pdo, $exercise, $resolvedTopicId);
                $exerciseUuid = trim((string) ($exercise['uuid'] ?? ''));
                if ($exerciseUuid === '' || $this->recordUuidExists($pdo, 'ct_esercizi', $exerciseUuid)) {
                    $exerciseUuid = $this->generateUuidV4();
                }
                $pdo->prepare('INSERT INTO ct_esercizi (uuid, testo_esercizio, testo_ese104, punti_esperienza, storia_esercizio, fk_argomento, tipo_esercizio, nome_capitolo, num_domande, monete_guadagnate, fk_materiale, livello_diff) VALUES (:uuid, :testo_esercizio, :testo_ese104, :punti_esperienza, :storia_esercizio, :fk_argomento, :tipo_esercizio, :nome_capitolo, :num_domande, :monete_guadagnate, :fk_materiale, :livello_diff)')
                    ->execute([
                        'uuid' => $exerciseUuid,
                        'testo_esercizio' => $this->importHtmlAssetPaths((string) ($exercise['testo_esercizio'] ?? ''), $extractDir, $newQuestId, $newQuestUuid),
                        'testo_ese104' => $this->importHtmlAssetPaths((string) ($exercise['testo_ese104'] ?? ''), $extractDir, $newQuestId, $newQuestUuid),
                        'punti_esperienza' => (int) ($exercise['punti_esperienza'] ?? 0),
                        'storia_esercizio' => $this->importHtmlAssetPaths((string) ($exercise['storia_esercizio'] ?? ''), $extractDir, $newQuestId, $newQuestUuid),
                        'fk_argomento' => $resolvedTopicId,
                        'tipo_esercizio' => (int) ($exercise['tipo_esercizio'] ?? 1),
                        'nome_capitolo' => (string) ($exercise['nome_capitolo'] ?? ''),
                        'num_domande' => (int) ($exercise['num_domande'] ?? 0),
                        'monete_guadagnate' => (int) ($exercise['monete_guadagnate'] ?? 0),
                        'fk_materiale' => $resolvedMaterialId,
                        'livello_diff' => (int) ($exercise['livello_diff'] ?? 1),
                    ]);
                $newExerciseId = (int) $pdo->lastInsertId();
                $pdo->prepare('INSERT INTO ct_esercizi_quest (fk_capitolo, fk_esercizio, progressivo) VALUES (:fk_capitolo, :fk_esercizio, :progressivo)')
                    ->execute(['fk_capitolo' => $newChapterId, 'fk_esercizio' => $newExerciseId, 'progressivo' => (int) ($exercise['progressivo'] ?? 1)]);
                $pdo->prepare('INSERT INTO ct_classi_esercizi_attivi (fk_classe, fk_capitolo, fk_esercizio, attivo) VALUES (:fk_classe, :fk_capitolo, :fk_esercizio, 0)')
                    ->execute(['fk_classe' => $classId, 'fk_capitolo' => $newChapterId, 'fk_esercizio' => $newExerciseId]);
                $this->importExerciseMaterialBindings($pdo, $newExerciseId, $exercise, $resolvedTopicId, $resolvedMaterialId);
            }
        }
        return $newQuestId;
    }

    private function questUuidExists(PDO $pdo, string $uuid): bool
    {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ct_quest WHERE uuid = :uuid');
        $stmt->execute(['uuid' => $uuid]);

        return (int) ($stmt->fetchColumn() ?: 0) > 0;
    }

    private function recordUuidExists(PDO $pdo, string $table, string $uuid): bool
    {
        $allowedTables = [
            'ct_capitoli' => true,
            'ct_esercizi' => true,
            'ct_quest' => true,
        ];
        if ($uuid === '' || !isset($allowedTables[$table])) {
            return false;
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE uuid = :uuid');
        $stmt->execute(['uuid' => $uuid]);

        return (int) ($stmt->fetchColumn() ?: 0) > 0;
    }

    private function resolveTopicIdForImportedExercise(PDO $pdo, array $exercise, array $topicResolution, array &$missingTopics): int
    {
        $legacyTopicId = (int) ($exercise['fk_argomento'] ?? 0);
        if ($legacyTopicId > 0) {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE id_argomento = :id_argomento LIMIT 1');
            $stmt->execute(['id_argomento' => $legacyTopicId]);
            $found = (int) ($stmt->fetchColumn() ?: 0);
            if ($found > 0) {
                return $found;
            }
        }

        $topic = (array) ($exercise['argomento'] ?? []);
        $topicUuid = trim((string) ($topic['uuid'] ?? ''));
        $topicName = trim((string) ($topic['nome'] ?? ''));
        $normalizedTopicName = $this->normalizeImportLookupName($topicName);
        $topicKey = $topicUuid !== '' ? ('uuid:' . $topicUuid) : ('name:' . $normalizedTopicName);

        if ($topicUuid !== '') {
            $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE uuid = :uuid LIMIT 1');
            $stmt->execute(['uuid' => $topicUuid]);
            $found = (int) ($stmt->fetchColumn() ?: 0);
            if ($found > 0) {
                return $found;
            }
        }

        if ($topicName !== '') {
            $found = $this->findTopicIdByImportedName($pdo, $topicName);
            if ($found > 0) {
                return $found;
            }
        }

        $decision = is_array($topicResolution[$topicKey] ?? null) ? $topicResolution[$topicKey] : [];
        $mode = (string) ($decision['mode'] ?? '');
        if ($mode === 'existing') {
            $existingTopicId = (int) ($decision['topic_id'] ?? 0);
            if ($existingTopicId > 0) {
                return $existingTopicId;
            }
        }

        if ($mode === 'create') {
            $subjectId = (int) ($decision['subject_id'] ?? 0);
            if ($subjectId > 0 && $topicName !== '') {
                $pdo->prepare('INSERT INTO ct_argomenti (uuid, nome_argomento, fk_materia) VALUES (:uuid, :nome_argomento, :fk_materia)')
                    ->execute([
                        'uuid' => $topicUuid !== '' ? $topicUuid : $this->generateUuidV4(),
                        'nome_argomento' => $topicName,
                        'fk_materia' => $subjectId,
                    ]);
                return (int) $pdo->lastInsertId();
            }
        }

        $missingTopics[$topicKey] = [
            'key' => $topicKey,
            'uuid' => $topicUuid,
            'nome' => $topicName !== '' ? $topicName : $this->translator->translate('teacher.quest.import.unnamed_topic'),
        ];

        return 0;
    }

    private function findTopicIdByImportedName(PDO $pdo, string $topicName): int
    {
        $stmt = $pdo->prepare('SELECT id_argomento FROM ct_argomenti WHERE nome_argomento = :nome_argomento LIMIT 1');
        $stmt->execute(['nome_argomento' => $topicName]);
        $found = (int) ($stmt->fetchColumn() ?: 0);
        if ($found > 0) {
            return $found;
        }

        $target = $this->normalizeImportLookupName($topicName);
        if ($target === '') {
            return 0;
        }

        $rows = $pdo->query('SELECT id_argomento, nome_argomento FROM ct_argomenti')->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($rows as $row) {
            if ($this->normalizeImportLookupName((string) ($row['nome_argomento'] ?? '')) === $target) {
                return (int) ($row['id_argomento'] ?? 0);
            }
        }

        return 0;
    }

    private function normalizeImportLookupName(string $value): string
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/\s+/u', ' ', trim($decoded));
        return mb_strtolower((string) $normalized, 'UTF-8');
    }

    private function resolveMaterialIdForImportedExercise(PDO $pdo, array $exercise, int $topicId): int
    {
        if ($topicId <= 0) {
            return 0;
        }

        $material = (array) ($exercise['materiale'] ?? []);
        $materialName = trim((string) ($material['nome'] ?? ''));
        if ($materialName !== '') {
            $found = $this->findMaterialIdByNameAndTopic($pdo, $materialName, $topicId);
            return $found > 0 ? $found : 0;
        }

        $legacyMaterialId = (int) ($exercise['fk_materiale'] ?? 0);
        if ($legacyMaterialId > 0) {
            $stmt = $pdo->prepare(
                'SELECT id_materiale
                 FROM ct_materiali
                 WHERE id_materiale = :id_materiale
                   AND fk_argomento = :fk_argomento
                 LIMIT 1'
            );
            $stmt->execute([
                'id_materiale' => $legacyMaterialId,
                'fk_argomento' => $topicId,
            ]);
            return (int) ($stmt->fetchColumn() ?: 0);
        }

        return 0;
    }

    private function importExerciseMaterialBindings(PDO $pdo, int $exerciseId, array $exercise, int $topicId, int $resolvedMaterialId): void
    {
        $bindings = is_array($exercise['materiali_collegati'] ?? null) ? $exercise['materiali_collegati'] : [];
        if ($bindings === [] && $resolvedMaterialId > 0) {
            $bindings[] = [
                'fk_materiale' => $resolvedMaterialId,
                'nome_materiale' => '',
                'link' => '',
            ];
        }

        if ($bindings === []) {
            return;
        }

        $insert = $pdo->prepare(
            'INSERT INTO ct_esercizio_materiali (fk_esercizio, fk_materiale, link)
             VALUES (:fk_esercizio, :fk_materiale, :link)'
        );

        $seen = [];
        foreach ($bindings as $binding) {
            $materialId = 0;
            $materialName = trim((string) ($binding['nome_materiale'] ?? ''));
            if ($materialName !== '') {
                $materialId = $this->findMaterialIdByNameAndTopic($pdo, $materialName, $topicId);
            } elseif ((int) ($binding['fk_materiale'] ?? 0) === (int) ($exercise['fk_materiale'] ?? 0)) {
                $materialId = $resolvedMaterialId;
            }

            $link = trim((string) ($binding['link'] ?? ''));
            if ($materialId <= 0 && $link === '') {
                continue;
            }

            $key = $materialId . '|' . $link;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $insert->execute([
                'fk_esercizio' => $exerciseId,
                'fk_materiale' => $materialId > 0 ? $materialId : null,
                'link' => $link !== '' ? $link : null,
            ]);
        }
    }

    private function findMaterialIdByNameAndTopic(PDO $pdo, string $materialName, int $topicId): int
    {
        $stmt = $pdo->prepare(
            'SELECT id_materiale
             FROM ct_materiali
             WHERE fk_argomento = :fk_argomento
               AND LOWER(TRIM(nome_materiale)) = LOWER(TRIM(:nome_materiale))
             LIMIT 1'
        );
        $stmt->execute([
            'fk_argomento' => $topicId,
            'nome_materiale' => $materialName,
        ]);

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    private function resolveAbsoluteAssetPath(string $path): ?string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//i', $path) === 1) {
            return null;
        }
        return dirname(__DIR__, 2) . '/public/' . ltrim($path, '/');
    }

    private function importHtmlAssetPaths(string $html, string $extractDir, ?int $questId = null, string $questUuid = ''): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $wasEncoded = str_contains($html, '&lt;img') || str_contains($html, '&lt;IMG');
        $workingHtml = $wasEncoded ? html_entity_decode($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : $html;
        $rewritten = (string) preg_replace_callback(
            '/(<img[^>]+src=["\'])([^"\']+)(["\'][^>]*>)/i',
            function (array $matches) use ($extractDir, $questId, $questUuid): string {
                return $matches[1] . $this->importAssetPath((string) $matches[2], $extractDir, $questId, $questUuid) . $matches[3];
            },
            $workingHtml
        );

        return $wasEncoded ? htmlentities($rewritten, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : $rewritten;
    }

    private function importAssetPath(string $path, string $extractDir, ?int $questId = null, string $questUuid = ''): string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//i', $path) === 1) {
            return $path;
        }

        $archivePath = ltrim($path, '/');
        if (!str_starts_with($archivePath, 'assets/')) {
            return $path;
        }

        $source = $extractDir . '/' . $archivePath;
        if (!is_file($source)) {
            return $path;
        }

        $isEditorImage = str_contains(str_replace('\\', '/', $archivePath), 'assets/images/Quest/Editor/');
        $targetDir = $isEditorImage && $questId !== null
            ? $this->ensureQuestEditorImageDirectory($questId, $questUuid)
            : dirname(__DIR__, 2) . '/public/assets/images/Quest';

        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return $path;
        }
        $targetName = uniqid('imported_', true) . '_' . basename($source);
        copy($source, $targetDir . '/' . $targetName);
        return $isEditorImage && $questId !== null
            ? $this->getQuestEditorImagePublicPath($questId, $questUuid) . '/' . $targetName
            : '/assets/images/Quest/' . $targetName;
    }

    private function getArchiveAssetRelativePath(string $path): string
    {
        $relative = str_replace('\\', '/', ltrim(trim($path), '/'));
        if (!str_starts_with($relative, 'assets/')) {
            return '';
        }

        $relative = substr($relative, strlen('assets/'));
        $parts = array_values(array_filter(explode('/', $relative), static fn (string $part): bool => $part !== '' && $part !== '.' && $part !== '..'));

        return implode('/', $parts);
    }

    private function getQuestEditorImageFolderName(int $questId, string $questUuid = ''): string
    {
        $questUuid = trim($questUuid);
        $suffix = $questUuid !== '' ? preg_replace('/[^a-z0-9_-]+/i', '_', $questUuid) : (string) $questId;

        return 'quest_' . $suffix;
    }

    private function getQuestEditorImageDirectory(int $questId, string $questUuid = ''): string
    {
        return dirname(__DIR__, 2) . '/public/assets/images/Quest/Editor/' . $this->getQuestEditorImageFolderName($questId, $questUuid);
    }

    private function getLegacyQuestEditorImageDirectory(int $questId): string
    {
        return dirname(__DIR__, 2) . '/public/assets/images/Quest/Editor/quest_' . $questId;
    }

    private function getQuestEditorImagePublicPath(int $questId, string $questUuid = ''): string
    {
        return '/assets/images/Quest/Editor/' . $this->getQuestEditorImageFolderName($questId, $questUuid);
    }

    private function ensureQuestEditorImageDirectory(int $questId, string $questUuid = ''): string
    {
        $targetDir = $this->getQuestEditorImageDirectory($questId, $questUuid);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        return $targetDir;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (array_diff(scandir($dir) ?: [], ['.', '..']) as $item) {
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $status = $permissionService->checkPermissionsTeacher();

        if ($status === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($status) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->translator->translate('studmanage.permission.session_expired')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->translator->translate('studmanage.permission.not_allowed')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->translator->translate('studmanage.permission.select_class_first')),
            default => $this->error($this->translator->translate('studmanage.permission.not_class_owner')),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();

        if ($classId === null) {
            throw new \RuntimeException($this->translator->translate('studmanage.permission.class_not_selected'));
        }

        return $classId;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
