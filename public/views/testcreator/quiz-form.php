<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quiz = is_array($quiz ?? null) ? $quiz : [];
$subjects = is_array($subjects ?? null) ? $subjects : [];
$topicsBySubject = is_array($topicsBySubject ?? null) ? $topicsBySubject : [];
$selectedTopics = is_array($selectedTopics ?? null) ? $selectedTopics : [];
$questionTypes = is_array($questionTypes ?? null) ? $questionTypes : [];
$selectedTypeRules = is_array($selectedTypeRules ?? null) ? $selectedTypeRules : [];
$grids = is_array($grids ?? null) ? $grids : [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';

$quizId = (int) ($quiz['id_quiz'] ?? 0);
$isEdit = $quizId > 0;
$selectedSubjectId = (int) ($quiz['fk_materia'] ?? 0);
$selectedTopicsMap = [];
foreach ($selectedTopics as $topicId) {
    $selectedTopicsMap[(int) $topicId] = true;
}
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $isEdit ? $translator->translate('testcreator.quizzes.form.edit_title') : $translator->translate('testcreator.quizzes.form.new_title') ?></h3>
        </div>

        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="/testcreator/quiz" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="<?= $isEdit ? '/testcreator/quiz/' . $quizId . '/update' : '/testcreator/quiz/save' ?>" id="testCreatorQuizForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quizName" class="form-label"><?= $translator->translate('testcreator.quizzes.field.name') ?></label>
                            <input type="text" class="form-control" id="quizName" name="nome_quiz" maxlength="255" required value="<?= htmlspecialchars((string) ($quiz['nome_quiz'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quizSubject" class="form-label"><?= $translator->translate('testcreator.quizzes.form.subject') ?></label>
                            <select id="quizSubject" name="materia" class="form-control" required <?= $isEdit ? 'disabled' : '' ?>>
                                <option value=""><?= $translator->translate('testcreator.topics.select_subject') ?></option>
                                <?php foreach ($subjects as $subject): ?>
                                    <?php $subjectId = (int) ($subject['id_materia'] ?? 0); ?>
                                    <option value="<?= $subjectId ?>" <?= $selectedSubjectId === $subjectId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="materia" value="<?= $selectedSubjectId ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= $translator->translate('testcreator.quizzes.form.topics') ?></label>
                        <input
                            type="text"
                            id="quizTopicSearch"
                            class="form-control mb-2"
                            placeholder="<?= htmlspecialchars($translator->translate('testcreator.quizzes.form.search_topic')) ?>"
                            autocomplete="off">
                        <div
                            id="quizTopicsContainer"
                            class="border rounded p-2"
                            style="max-height: 260px; overflow-y: auto;"
                            data-topics='<?= htmlspecialchars((string) json_encode($topicsBySubject, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'
                            data-selected-topics='<?= htmlspecialchars((string) json_encode(array_keys($selectedTopicsMap), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 mb-1">
                            <small class="text-muted"><?= $translator->translate('testcreator.quizzes.form.selected_topics') ?></small>
                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" id="quizTopicsClearSelection"><?= $translator->translate('testcreator.quizzes.form.clear') ?></button>
                        </div>
                        <div id="quizTopicsSelected" class="d-flex flex-wrap gap-2"></div>
                        <div id="quizTopicsHiddenInputs"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="showPoints" class="form-label"><?= $translator->translate('testcreator.quizzes.form.show_points') ?></label>
                            <select id="showPoints" name="mostrapunti" class="form-control">
                                <option value="1" <?= (int) ($quiz['mostra_punti_dom'] ?? 1) === 1 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.yes') ?></option>
                                <option value="2" <?= (int) ($quiz['mostra_punti_dom'] ?? 1) === 2 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.no') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mixQuestions" class="form-label"><?= $translator->translate('testcreator.quizzes.form.mix_questions') ?></label>
                            <select id="mixQuestions" name="mix_questions" class="form-control">
                                <option value="0" <?= (int) ($quiz['mix_questions'] ?? 0) === 0 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.yes') ?></option>
                                <option value="1" <?= (int) ($quiz['mix_questions'] ?? 0) === 1 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.no') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="gridId" class="form-label"><?= $translator->translate('testcreator.quizzes.form.grid') ?></label>
                            <select id="gridId" name="griglia" class="form-control">
                                <option value="0"><?= $translator->translate('testcreator.quizzes.form.no_grid') ?></option>
                                <?php foreach ($grids as $grid): ?>
                                    <?php $gridId = (int) ($grid['id_griglia'] ?? 0); ?>
                                    <option value="<?= $gridId ?>" <?= (int) ($quiz['fk_griglia'] ?? 0) === $gridId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars((string) ($grid['nome_griglia'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isRandom" class="form-label"><?= $translator->translate('testcreator.quizzes.form.random_questions') ?></label>
                            <?php $isRandom = (int) ($quiz['casuale'] ?? 1) === 1; ?>
                            <select id="isRandom" name="acaso" class="form-control" <?= $isEdit ? 'disabled' : '' ?>>
                                <option value="si" <?= $isRandom ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.yes') ?></option>
                                <option value="no" <?= !$isRandom ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.no') ?></option>
                            </select>
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="acaso" value="<?= $isRandom ? 'si' : 'no' ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mixAnswers" class="form-label"><?= $translator->translate('testcreator.quizzes.form.mix_answers') ?></label>
                            <select id="mixAnswers" name="mix_answer" class="form-control">
                                <option value="si" <?= (int) ($quiz['mix_answer'] ?? 1) === 1 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.yes') ?></option>
                                <option value="no" <?= (int) ($quiz['mix_answer'] ?? 1) === 0 ? 'selected' : '' ?>><?= $translator->translate('testcreator.common.no') ?></option>
                            </select>
                        </div>
                    </div>

                    <div id="quizTypeRules" data-visible="<?= $isRandom ? '1' : '0' ?>">
                        <label class="form-label"><?= $translator->translate('testcreator.quizzes.form.type_rules') ?></label>
                        <div id="quizTypeRulesRows">
                            <?php
                            $rules = $selectedTypeRules;
                            if ($rules === []) {
                                $rules[] = ['fk_tipo_domande' => 0, 'num_domande' => 1];
                            }
                            foreach ($rules as $index => $rule):
                            ?>
                                <div class="row mb-2 js-rule-row">
                                    <div class="col-md-7">
                                        <select name="tipo_domande[]" class="form-control">
                                            <option value="0" <?= (int) ($rule['fk_tipo_domande'] ?? 0) === 0 ? 'selected' : '' ?>><?= $translator->translate('testcreator.quizzes.form.any_type') ?></option>
                                            <?php foreach ($questionTypes as $questionType): ?>
                                                <?php
                                                $questionTypeId = (int) ($questionType['id_tipo_domanda'] ?? 0);
                                                $questionTypeText = $useEnglishDbTranslations && trim((string) ($questionType['tipo_en'] ?? '')) !== '' ? (string) $questionType['tipo_en'] : (string) ($questionType['tipo'] ?? '');
                                                ?>
                                                <option value="<?= $questionTypeId ?>" <?= (int) ($rule['fk_tipo_domande'] ?? 0) === $questionTypeId ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($questionTypeText) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" min="1" name="num_domande[]" class="form-control" value="<?= max(1, (int) ($rule['num_domande'] ?? 1)) ?>">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <?php if ($index === 0): ?>
                                            <button type="button" class="btn btn-success btn-sm js-add-rule">+</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-danger btn-sm js-remove-rule">-</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.quizzes.form.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
