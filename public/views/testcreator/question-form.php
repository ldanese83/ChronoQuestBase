<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$topic = is_array($topic ?? null) ? $topic : null;
$question = is_array($question ?? null) ? $question : null;
$books = is_array($books ?? null) ? $books : [];
$questionTypes = is_array($questionTypes ?? null) ? $questionTypes : [];
$answers = is_array($answers ?? null) ? $answers : [];
$formAction = (string) ($formAction ?? '/testcreator/domande/save');
$uploadImageUrl = (string) ($uploadImageUrl ?? '/testcreator/domande/api/editor/upload-image');
$isEdit = $question !== null;
$currentType = (int) ($question['fk_tipo_domanda'] ?? 1);
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $topic !== null): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $isEdit ? $translator->translate('testcreator.questions.form.edit_title') : $translator->translate('testcreator.questions.form.new_title') ?> - <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?></h3>
        </div>

        <div class="mb-3"><a href="/testcreator/domande/argomenti/<?= (int) ($topic['id_argomento'] ?? 0) ?>" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a></div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="<?= htmlspecialchars($formAction) ?>" id="questionForm">
                    <input type="hidden" name="id_argomento" value="<?= (int) ($topic['id_argomento'] ?? 0) ?>">
                    <div class="form-group mb-3"><label><?= $translator->translate('testcreator.questions.list.question') ?></label><input type="text" class="form-control" name="domanda" required value="<?= htmlspecialchars(html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?>"></div>
                    <div class="form-row row">
                        <div class="form-group col-md-3 mb-3"><label><?= $translator->translate('testcreator.questions.form.points') ?></label><input type="number" step="0.1" name="punti" class="form-control" required value="<?= htmlspecialchars((string) ($question['punti'] ?? '1')) ?>"></div>
                        <div class="form-group col-md-3 mb-3"><label><?= $translator->translate('testcreator.questions.form.difficulty') ?></label><select name="livello_diff" class="form-control"><?php for ($i = 1; $i <= 5; $i++): ?><option value="<?= $i ?>" <?= (int) ($question['livello_diff'] ?? 3) === $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select></div>
                        <div class="form-group col-md-3 mb-3"><label><?= $translator->translate('testcreator.questions.form.group_number') ?></label><input type="number" name="num_gruppo" class="form-control" value="<?= htmlspecialchars((string) ($question['num_gruppo'] ?? '0')) ?>"></div>
                        <div class="form-group col-md-3 mb-3"><label><?= $translator->translate('testcreator.questions.form.answer_rows') ?></label><input type="number" id="num_righe" name="num_righe" class="form-control" value="<?= htmlspecialchars((string) ($question['num_righe'] ?? '0')) ?>"></div>
                    </div>

                    <div class="form-group mb-3"><label><?= $translator->translate('testcreator.questions.form.reference_book') ?></label><select name="libro" class="form-control" required><?php foreach ($books as $book): ?><option value="<?= (int) ($book['id_libro_testo'] ?? 0) ?>" <?= (int) ($question['fk_libro'] ?? 0) === (int) ($book['id_libro_testo'] ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($book['titolo_libro'] ?? '') . ' - ' . (string) ($book['autori'] ?? '') . ' - ' . (string) ($book['casa_editrice'] ?? '')) ?></option><?php endforeach; ?></select></div>

                    <div class="form-group mb-3"><label><?= $translator->translate('testcreator.questions.form.question_type') ?></label>
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="tipo_domanda" id="tipo_domanda" value="<?= $currentType ?>">
                            <select id="tipo_domanda_locked" class="form-control" disabled>
                                <?php foreach ($questionTypes as $type): ?>
                                    <?php $typeText = $useEnglishDbTranslations && trim((string) ($type['tipo_en'] ?? '')) !== '' ? (string) $type['tipo_en'] : (string) ($type['tipo'] ?? ''); ?>
                                    <option value="<?= (int) ($type['id_tipo_domanda'] ?? 0) ?>" <?= (int) ($type['id_tipo_domanda'] ?? 0) === $currentType ? 'selected' : '' ?>><?= htmlspecialchars($typeText) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <select name="tipo_domanda" id="tipo_domanda" class="form-control">
                                <?php foreach ($questionTypes as $type): ?>
                                    <?php $typeText = $useEnglishDbTranslations && trim((string) ($type['tipo_en'] ?? '')) !== '' ? (string) $type['tipo_en'] : (string) ($type['tipo'] ?? ''); ?>
                                    <option value="<?= (int) ($type['id_tipo_domanda'] ?? 0) ?>"><?= htmlspecialchars($typeText) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div id="answersWrapper" class="mb-3">
                        <label><?= $translator->translate('testcreator.questions.form.possible_answers') ?></label>
                        <div id="answersContainer">
                            <?php $rows = count($answers) > 0 ? $answers : [['risposta' => '', 'corretta' => 0], ['risposta' => '', 'corretta' => 0]]; ?>
                            <?php foreach ($rows as $index => $answer): ?>
                                <div class="input-group mb-2 answer-row">
                                    <input type="text" class="form-control" name="risposta[]" value="<?= htmlspecialchars(html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?>">
                                    <div class="input-group-append input-group-text"><input type="checkbox" class="answer-correct" <?= (int) ($answer['corretta'] ?? 0) === 1 ? 'checked' : '' ?>></div>
                                    <input type="hidden" name="corretta[]" value="<?= (int) ($answer['corretta'] ?? 0) === 1 ? 'si' : 'no' ?>">
                                    <button type="button" class="btn btn-outline-danger js-remove-answer">-</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addAnswerBtn"><?= $translator->translate('testcreator.questions.form.add_answer') ?></button>
                    </div>

                    <div id="numericExerciseWrapper" class="mb-3" style="display:none;">
                        <label><?= $translator->translate('testcreator.questions.form.numeric_exercise_text') ?></label>
                        <textarea id="ese_num" name="ese_num" class="form-control" rows="10"><?= htmlspecialchars(html_entity_decode((string) ($question['ese_num'] ?? $translator->translate('testcreator.questions.form.numeric_exercise_default')), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?></textarea>
                        <small class="form-text text-muted"><?= $translator->translate('testcreator.questions.form.tinymce_note') ?> <code>assets/images/Questions</code>.</small>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.subjects.modal.save') ?></button>
                </form>
            </div>
        </div>
        <script>window.testCreatorQuestionFormData = { currentType: <?= $currentType ?>, uploadImageUrl: <?= json_encode($uploadImageUrl) ?> };</script>
    <?php endif; ?>
</div>
