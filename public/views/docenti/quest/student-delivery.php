<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exercise = $exercise ?? null;
$delivery = $studentDelivery ?? null;
$quizOpenAnswers = $quizOpenAnswers ?? [];
$quizMultipleChoiceAnswers = $quizMultipleChoiceAnswers ?? [];
$quizMultipleChoiceScore = $quizMultipleChoiceScore ?? null;
$submittedFiles = $submittedFiles ?? [];
$isEvaluated = (int) ($delivery['valutato'] ?? 0) === 1;
$exerciseType = (int) ($exercise['tipo_esercizio'] ?? 0);
$suggestedScore = ($exerciseType === 2 && is_array($quizMultipleChoiceScore)) ? (int) ($quizMultipleChoiceScore['grade'] ?? 1) : 0;
$gradeValue = $isEvaluated ? (int) ($delivery['valutazione'] ?? 0) : ($exerciseType === 2 ? $suggestedScore : 0);
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null && $exercise !== null && $delivery !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.quest.delivery.student.title') ?> <strong><?= htmlspecialchars((string) (($delivery['cognome'] ?? '') . ' ' . ($delivery['nome'] ?? ''))) ?></strong></h1>
                <h2 class="h5 mb-0 text-gray-700 mt-2"><?= $translator->translate('teacher.quest.student_delivery.exercise_label') ?>: <strong><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></strong></h2>
            </div>
            <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/consegne" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.student_delivery.back_to_deliveries') ?>
            </a>
        </div>

        <form id="studentDeliveryForm" method="POST" action="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/consegne/<?= (int) ($delivery['id_studente'] ?? 0) ?>/save-valutazione" style="max-width:95%;">
            <div class="mb-3">
                <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.exercise_text') ?></label>
                <div class="alert alert-warning" style="font-size:1.1rem;">
                    <?php
                    $isL104 = (int) ($delivery['l104'] ?? 0) === 1;
                    $simplified = trim((string) ($exercise['testo_ese104'] ?? ''));
                    $exerciseText = $isL104 && $simplified !== '' ? $simplified : (string) ($exercise['testo_esercizio'] ?? '');
                    echo html_entity_decode($exerciseText);
                    ?>
                </div>
            </div>

            <?php if ($exerciseType === 1): ?>
                <div class="mb-3">
                    <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.student_answer') ?></label>
                    <div class="card card-body"><?= html_entity_decode((string) ($delivery['risposta_aperta'] ?? '')) ?></div>
                </div>
            <?php elseif ($exerciseType === 2): ?>
                <?php foreach ($quizMultipleChoiceAnswers as $index => $question): ?>
                    <div class="mb-3">
                        <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.question') ?> <?= (int) $index + 1 ?></label>
                        <div class="alert alert-warning"><?= html_entity_decode((string) ($question['domanda'] ?? '')) ?></div>
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th><?= $translator->translate('teacher.quest.student_delivery.answer') ?></th>
                                <th class="text-center" style="width:170px;"><?= $translator->translate('teacher.quest.student_delivery.student') ?></th>
                                <th class="text-center" style="width:170px;"><?= $translator->translate('teacher.quest.student_delivery.correct') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (($question['answers'] ?? []) as $answer): ?>
                                <?php
                                $isSelected = (int) ($answer['selected_by_student'] ?? 0) === 1;
                                $isCorrect = (int) ($answer['corretta'] ?? 0) === 1;
                                $rowClass = '';
                                if ($isSelected && $isCorrect) {
                                    $rowClass = 'table-success';
                                } elseif ($isSelected && !$isCorrect) {
                                    $rowClass = 'table-danger';
                                }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= html_entity_decode((string) ($answer['risposta'] ?? '')) ?></td>
                                    <td class="text-center"><?= $isSelected ? '<i class="fas fa-check"></i>' : '-' ?></td>
                                    <td class="text-center"><?= $isCorrect ? '<i class="fas fa-check text-success"></i>' : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>

                <?php if (is_array($quizMultipleChoiceScore)): ?>
                    <div class="alert alert-info">
                        <?= $translator->translate('teacher.quest.student_delivery.correct_answers') ?>: <strong><?= (int) ($quizMultipleChoiceScore['correct_answers'] ?? 0) ?>/<?= (int) ($quizMultipleChoiceScore['total_questions'] ?? 0) ?></strong>.
                        <?= $translator->translate('teacher.quest.student_delivery.suggested_grade') ?>: <strong><?= (int) ($quizMultipleChoiceScore['grade'] ?? 1) ?>/10</strong>.
                    </div>
                <?php endif; ?>
            <?php elseif ($exerciseType === 3): ?>
                <div class="mb-3">
                    <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.submitted_files') ?></label>
                    <?php if (count($submittedFiles) === 0): ?>
                        <div><em><?= $translator->translate('teacher.quest.student_delivery.no_submitted_files') ?></em></div>
                    <?php else: ?>
                        <table class="table table-sm table-bordered">
                            <thead><tr><th><?= $translator->translate('teacher.quest.student_delivery.file_name') ?></th><th class="text-center"><?= $translator->translate('teacher.quest.student_delivery.actions') ?></th></tr></thead>
                            <tbody>
                            <?php foreach ($submittedFiles as $file): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($file['name'] ?? '')) ?></td>
                                    <td class="text-center"><a href="<?= htmlspecialchars((string) ($file['path'] ?? '')) ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php elseif ($exerciseType === 4): ?>
                <?php foreach ($quizOpenAnswers as $answer): ?>
                    <div class="mb-3">
                        <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.open_question') ?></label>
                        <div class="alert alert-warning"><?= html_entity_decode((string) ($answer['domanda'] ?? '')) ?></div>
                        <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.student_answer') ?></label>
                        <div class="card card-body mb-2"><?= html_entity_decode((string) ($answer['testo_risposta'] ?? '')) ?></div>
                        <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.teacher_comment') ?></label>
                        <?php if ($isEvaluated): ?>
                            <div class="card card-body"><?= html_entity_decode((string) ($answer['commento_prof'] ?? '')) ?></div>
                        <?php else: ?>
                            <textarea class="form-control quest-comment" name="commento_domanda[<?= (int) ($answer['id_domanda'] ?? 0) ?>]" rows="5"><?= htmlspecialchars((string) ($answer['commento_prof'] ?? '')) ?></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($quizMultipleChoiceAnswers as $index => $question): ?>
                    <div class="mb-3">
                        <label class="form-label"><?= $translator->translate('teacher.quest.student_delivery.question') ?> <?= (int) $index + 1 ?></label>
                        <div class="alert alert-warning"><?= html_entity_decode((string) ($question['domanda'] ?? '')) ?></div>
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th><?= $translator->translate('teacher.quest.student_delivery.answer') ?></th>
                                <th class="text-center" style="width:170px;"><?= $translator->translate('teacher.quest.student_delivery.student') ?></th>
                                <th class="text-center" style="width:170px;"><?= $translator->translate('teacher.quest.student_delivery.correct') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (($question['answers'] ?? []) as $answer): ?>
                                <?php
                                $isSelected = (int) ($answer['selected_by_student'] ?? 0) === 1;
                                $isCorrect = (int) ($answer['corretta'] ?? 0) === 1;
                                $rowClass = '';
                                if ($isSelected && $isCorrect) {
                                    $rowClass = 'table-success';
                                } elseif ($isSelected && !$isCorrect) {
                                    $rowClass = 'table-danger';
                                }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= html_entity_decode((string) ($answer['risposta'] ?? '')) ?></td>
                                    <td class="text-center"><?= $isSelected ? '<i class="fas fa-check"></i>' : '-' ?></td>
                                    <td class="text-center"><?= $isCorrect ? '<i class="fas fa-check text-success"></i>' : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <?php if (is_array($quizMultipleChoiceScore)): ?>
                    <div class="alert alert-info">
                        <?= $translator->translate('teacher.quest.student_delivery.correct_answers') ?>: <strong><?= (int) ($quizMultipleChoiceScore['correct_answers'] ?? 0) ?>/<?= (int) ($quizMultipleChoiceScore['total_questions'] ?? 0) ?></strong>.
                        <?= $translator->translate('teacher.quest.student_delivery.multiple_choice_grade') ?>: <strong><?= (int) ($quizMultipleChoiceScore['grade'] ?? 1) ?>/10</strong>.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($exerciseType !== 4): ?>
                <div class="mb-3">
                    <label for="commento" class="form-label"><?= $translator->translate('teacher.quest.student_delivery.comment') ?></label>
                    <?php if ($isEvaluated): ?>
                        <div class="card card-body"><?= html_entity_decode((string) ($delivery['commento_prof'] ?? '')) ?></div>
                    <?php else: ?>
                        <textarea id="commento" name="commento" class="form-control quest-comment" rows="8"><?= htmlspecialchars((string) ($delivery['commento_prof'] ?? '')) ?></textarea>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!$isEvaluated && $exerciseType !== 2 && $exerciseType !== 4): ?>
                <div class="mb-3">
                    <button type="button" id="gemini-valutazione-btn" class="btn btn-warning">
                        <i class="fas fa-magic"></i> <?= $translator->translate('teacher.quest.student_delivery.gemini_button') ?>
                    </button>
                    <div id="gemini-message" class="alert alert-warning d-none mt-2"></div>
                </div>
            <?php elseif ($isEvaluated): ?>
                <div class="alert alert-info"><?= $translator->translate('teacher.quest.student_delivery.already_evaluated') ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="valutazione" class="form-label"><?= $translator->translate('teacher.quest.student_delivery.evaluation') ?></label>
                <input type="number" min="1" max="10" step="1" class="form-control" id="valutazione" name="valutazione" value="<?= $gradeValue ?>" <?= $isEvaluated ? 'readonly' : 'required' ?> style="max-width:180px;">
            </div>

            <?php if (!$isEvaluated): ?>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i><?= $translator->translate('teacher.quest.student_delivery.save_evaluation') ?></button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<script src="/assets/tinymce/tinymce.min.js"></script>
<script>
    window.studentDeliveryData = {
        suggestUrl: '/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/consegne/<?= (int) ($delivery['id_studente'] ?? 0) ?>/suggerisci-gemini',
        isEvaluated: <?= $isEvaluated ? 'true' : 'false' ?>,
    };
</script>
