<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exerciseTypes = $exerciseTypes ?? [];
$subjects = $subjects ?? [];
$topics = $topics ?? [];
$materials = $materials ?? [];
$exercise = $exercise ?? null;
$selectedMaterials = $selectedMaterials ?? [];
$materialLinks = $materialLinks ?? [];
$exerciseMode = $exerciseMode ?? 'create';
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
$isReadOnly = $exerciseMode === 'view';
$isEditMode = $exerciseMode === 'edit' || $exerciseMode === 'view';
$selectedTopicId = (int) ($exercise['fk_argomento'] ?? ($topics[0]['id_argomento'] ?? 0));
$selectedSubjectId = (int) ($exercise['fk_materia'] ?? 0);
$savePath = $isEditMode
    ? '/docenti/quest/' . (int) ($quest['id_quest'] ?? 0) . '/capitolo/' . (int) ($chapter['id_capitolo'] ?? 0) . '/esercizi/' . (int) ($exercise['id_esercizio'] ?? 0) . '/update'
    : '/docenti/quest/' . (int) ($quest['id_quest'] ?? 0) . '/capitolo/' . (int) ($chapter['id_capitolo'] ?? 0) . '/save';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $isEditMode ? ($isReadOnly ? $translator->translate('teacher.quest.add_exercise.mode.view') : $translator->translate('teacher.quest.add_exercise.mode.edit')) : $translator->translate('teacher.quest.add_exercise.mode.add') ?> <?= $translator->translate('teacher.quest.add_exercise.title_suffix') ?> <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong>
                <?= $translator->translate('teacher.quest.chapter_detail.chapter_label') ?>: <strong><?= htmlspecialchars((string) ($chapter['nome_capitolo'] ?? '')) ?></strong>
            </h1>
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitoli/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.add_exercise.back_to_chapter') ?>
                </a>
            </div>
        </div>

        <div id="add-exercise-alert" class="d-none"></div>

        <div class="row" style="width:100%;margin:auto;">
            <form action="<?= $savePath ?>" method="POST" id="form_capitolo" class="quest-add-exercise-form" style="width:90%;">
                <div class="mb-3">
                    <label for="nome_capitolo" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.name') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                        <input type="text" class="form-control" name="nome_capitolo" id="nome_capitolo" placeholder="<?= htmlspecialchars($translator->translate('teacher.quest.add_exercise.placeholder.name')) ?>" value="<?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?>" <?= $isReadOnly ? 'disabled' : '' ?> required>
                    </div>
                </div>
                <?php if ($isEditMode): ?>
                    <div class="mb-3">
                        <label for="progressivo" class="form-label"><?= $translator->translate('teacher.quest.chapter_list.field.progressive') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bars"></i></span>
                            <input type="number" class="form-control" name="progressivo" id="progressivo" value="<?= (int) ($exercise['progressivo'] ?? 1) ?>" <?= $isReadOnly ? 'disabled' : '' ?> required>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="tipo_esercizio" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.exercise_type') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <select id="tipo_esercizio" name="tipo_esercizio" class="form-select" style="width:90%;" <?= $isReadOnly ? 'disabled' : '' ?> required>
                            <?php foreach ($exerciseTypes as $type): ?>
                                <?php
                                $typeId = (int) ($type['id_tipo_esercizio'] ?? 0);
                                $translatedExerciseType = trim((string) ($type['tipo_en'] ?? ''));
                                $displayExerciseType = $useEnglishDbTranslations && $translatedExerciseType !== ''
                                    ? $translatedExerciseType
                                    : (string) ($type['tipo'] ?? '');
                                ?>
                                <option value="<?= $typeId ?>" <?= $typeId === (int) ($exercise['tipo_esercizio'] ?? $typeId) ? 'selected' : '' ?>><?= htmlspecialchars($displayExerciseType) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="livello_diff" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.difficulty') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-signal"></i></span>
                        <select id="livello_diff" name="livello_diff" class="form-select" style="width:90%;" <?= $isReadOnly ? 'disabled' : '' ?>>
                            <?php $diff = (int) ($exercise['livello_diff'] ?? 1); ?>
                            <option value="0" <?= $diff === 0 ? 'selected' : '' ?>><?= $translator->translate('teacher.quest.add_exercise.difficulty.easy') ?></option>
                            <option value="1" <?= $diff === 1 ? 'selected' : '' ?>><?= $translator->translate('teacher.quest.add_exercise.difficulty.medium') ?></option>
                            <option value="2" <?= $diff === 2 ? 'selected' : '' ?>><?= $translator->translate('teacher.quest.add_exercise.difficulty.hard') ?></option>
                            <option value="3" <?= $diff === 3 ? 'selected' : '' ?>><?= $translator->translate('teacher.quest.add_exercise.difficulty.very_hard') ?></option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="materia" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.subject') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <select id="materia" name="materia" class="form-select" style="width:90%;" data-topics-url-template="/docenti/quest/api/materie/{id}/argomenti" <?= $isEditMode ? 'disabled' : '' ?>>
                            <?php foreach ($subjects as $index => $subject): ?>
                                <?php $subjectId = (int) ($subject['id_materia'] ?? 0); ?>
                                <option value="<?= $subjectId ?>" <?= $selectedSubjectId > 0 ? ($selectedSubjectId === $subjectId ? 'selected' : '') : ($index === 0 ? 'selected' : '') ?>><?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="argomento" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.topic') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <select id="argomento" name="argomento" class="form-select" style="width:90%;" data-materials-url-template="/docenti/quest/api/argomenti/{id}/materiali" <?= $isEditMode ? 'disabled' : '' ?> required>
                            <?php foreach ($topics as $topic): ?>
                                <?php $topicId = (int) ($topic['id_argomento'] ?? 0); ?>
                                <option value="<?= $topicId ?>" <?= $selectedTopicId === $topicId ? 'selected' : '' ?>><?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="materiale" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.materials') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <select id="materiale" name="materiali[]" class="form-select" style="width:90%;" multiple size="6" <?= $isReadOnly ? 'disabled' : '' ?>>
                            <?php foreach ($materials as $material): ?>
                                <?php $materialId = (int) ($material['id_materiale'] ?? 0); ?>
                                <option value="<?= $materialId ?>" <?= in_array($materialId, $selectedMaterials, true) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($material['nome_materiale'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <small class="form-text text-muted"><?= $translator->translate('teacher.quest.add_exercise.materials.help') ?></small>
                </div>

                <div class="mb-3">
                    <label for="link_materiali" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.support_links') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                        <textarea id="link_materiali" name="link_materiali" class="form-control" rows="4" placeholder="https://www.youtube.com/...&#10;https://www.w3schools.com/..." style="width:90%;" <?= $isReadOnly ? 'disabled' : '' ?>><?= htmlspecialchars(implode("\n", $materialLinks)) ?></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="xp_points" class="form-label"><?= $translator->translate('teacher.quest.chapter_detail.field.experience_points') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                        <input type="number" class="form-control" id="xp_points" name="xp_points" value="<?= htmlspecialchars((string) ($exercise['punti_esperienza'] ?? '')) ?>" placeholder="<?= htmlspecialchars($translator->translate('teacher.quest.add_exercise.placeholder.experience_points')) ?>" <?= $isReadOnly ? 'disabled' : '' ?>>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="money" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.coins') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                        <input type="number" class="form-control" id="money" name="money" value="<?= htmlspecialchars((string) ($exercise['monete_guadagnate'] ?? '')) ?>" placeholder="<?= htmlspecialchars($translator->translate('teacher.quest.add_exercise.placeholder.coins')) ?>" <?= $isReadOnly ? 'disabled' : '' ?>>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="story" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.story') ?></label>
                    <div class="input-group quest-editor-group">
                        <textarea id="story" name="story" rows="20" class="form-control quest-richtext" <?= $isReadOnly ? 'disabled' : '' ?>><?= htmlspecialchars(html_entity_decode((string) ($exercise['storia_esercizio'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></textarea>
                    </div>
                    <small class="form-text text-muted"><?= $translator->translate('teacher.quest.add_exercise.editor.license_hint') ?></small>
                </div>

                <div class="mb-3">
                    <label for="num_domande" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.quiz_questions') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                        <input type="number" class="form-control" id="num_domande" name="num_domande" value="<?= htmlspecialchars((string) ($exercise['num_domande'] ?? '')) ?>" placeholder="<?= htmlspecialchars($translator->translate('teacher.quest.add_exercise.placeholder.quiz_questions')) ?>" <?= $isReadOnly ? 'disabled' : '' ?>>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="testo_esercizio" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.exercise_text') ?></label>
                    <div class="input-group quest-editor-group">
                        <textarea id="testo_esercizio" name="testo_esercizio" rows="20" class="form-control quest-richtext" <?= $isReadOnly ? 'disabled' : '' ?>><?= htmlspecialchars(html_entity_decode((string) ($exercise['testo_esercizio'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></textarea>
                    </div>
                    <div class="mt-2 <?= $isReadOnly ? 'd-none' : '' ?>">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-insert-math="inline" data-editor-target="testo_esercizio"><?= $translator->translate('teacher.quest.add_exercise.math.inline') ?></button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-insert-math="block" data-editor-target="testo_esercizio"><?= $translator->translate('teacher.quest.add_exercise.math.block') ?></button>
                    </div>
                </div>
                <?php if ($isEditMode): ?>
                    <div class="mb-3">
                        <label for="testo_esercizio104" class="form-label"><?= $translator->translate('teacher.quest.add_exercise.field.simplified_exercise') ?></label>
                        <div class="input-group quest-editor-group">
                            <textarea id="testo_esercizio104" name="testo_esercizio104" rows="20" class="form-control quest-richtext" <?= $isReadOnly ? 'disabled' : '' ?>><?= htmlspecialchars(html_entity_decode((string) ($exercise['testo_ese104'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></textarea>
                        </div>
                        <div class="mt-2 <?= $isReadOnly ? 'd-none' : '' ?>">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-insert-math="inline" data-editor-target="testo_esercizio104"><?= $translator->translate('teacher.quest.add_exercise.math.inline') ?></button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-insert-math="block" data-editor-target="testo_esercizio104"><?= $translator->translate('teacher.quest.add_exercise.math.block') ?></button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($isEditMode): ?>
                    <input type="hidden" name="argomento" value="<?= $selectedTopicId ?>">
                    <input type="hidden" name="tipo_esercizio" value="<?= (int) ($exercise['tipo_esercizio'] ?? 0) ?>">
                <?php endif; ?>

                <div class="row_form <?= $isReadOnly ? 'd-none' : '' ?>" style="margin-top:3vh">
                    <div class="input-group input-group-icon">
                        <button class="btn btn-success btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text"><?= $isEditMode ? $translator->translate('teacher.quest.add_exercise.button.update') : $translator->translate('teacher.quest.add_exercise.button.save') ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="/assets/tinymce/tinymce.min.js"></script>
<script>
    window.questAddExerciseData = {
        saveUrl: '<?= $savePath ?>',
        uploadUrl: '/docenti/quest/api/editor/upload-image?quest_id=<?= (int) ($quest['id_quest'] ?? 0) ?>',
        readOnly: <?= $isReadOnly ? 'true' : 'false' ?>,
    };
</script>
