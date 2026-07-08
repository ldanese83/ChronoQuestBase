<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quiz = is_array($quiz ?? null) ? $quiz : [];
$questions = is_array($questions ?? null) ? $questions : [];
$quizId = (int) ($quiz['id_quiz'] ?? 0);
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.quizzes.generate.title') ?> <?= htmlspecialchars((string) ($quiz['nome_quiz'] ?? '')) ?></h3>
        </div>

        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="/testcreator/quiz" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
            <a href="/testcreator/quiz/<?= $quizId ?>/genera" class="btn btn-dark btn-sm"><?= $translator->translate('testcreator.quizzes.generate.regenerate') ?></a>
            <a href="/testcreator/quiz/<?= $quizId ?>/stampa" class="btn btn-success btn-sm" target="_blank" rel="noopener"><?= $translator->translate('testcreator.quizzes.generate.print') ?></a>
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#dsaModal"><?= $translator->translate('testcreator.quizzes.generate.print_dsa') ?></button>
            <a href="/testcreator/quiz/<?= $quizId ?>/esporta-csv" class="btn btn-info btn-sm" target="_blank" rel="noopener"><?= $translator->translate('testcreator.quizzes.generate.export_csv') ?></a>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cocorrezioneModal"><?= $translator->translate('testcreator.quizzes.generate.print_cocorrection') ?></button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="border rounded p-3 mb-3">
                        <h6 class="fw-bold mb-2"><?= $translator->translate('testcreator.questions.list.question') ?> <?= (int) $index + 1 ?></h6>
                        <div><?= html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES) ?></div>
                        <?php if ((int) ($question['fk_tipo_domanda'] ?? 0) !== 1): ?>
                            <ul class="mt-2 mb-0">
                                <?php foreach (($question['answers'] ?? []) as $answer): ?>
                                    <li><?= html_entity_decode((string) ($answer['risposta'] ?? ''), ENT_QUOTES) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="modal fade" id="dsaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/testcreator/quiz/<?= $quizId ?>/stampa-dsa" method="GET" target="_blank">
                        <div class="modal-header"><h5 class="modal-title"><?= $translator->translate('testcreator.quizzes.generate.dsa_options') ?></h5></div>
                        <div class="modal-body">
                            <label class="d-block mb-2"><input type="checkbox" name="perc20meno"> <?= $translator->translate('testcreator.quizzes.generate.dsa.less_questions') ?></label>
                            <label class="d-block mb-2"><input type="checkbox" name="risp3"> <?= $translator->translate('testcreator.quizzes.generate.dsa.three_answers') ?></label>
                            <label class="d-block mb-2"><input type="checkbox" name="testobig"> <?= $translator->translate('testcreator.quizzes.generate.dsa.large_text') ?></label>
                            <label class="d-block"><input type="checkbox" name="opendyslexic"> <?= $translator->translate('testcreator.quizzes.generate.dsa.opendyslexic') ?></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.quizzes.generate.print') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cocorrezioneModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/testcreator/quiz/<?= $quizId ?>/co-correzione" method="GET" target="_blank">
                        <div class="modal-header"><h5 class="modal-title"><?= $translator->translate('testcreator.quizzes.generate.cocorrection_title') ?></h5></div>
                        <div class="modal-body">
                            <label for="qtaCocorrezione" class="form-label"><?= $translator->translate('testcreator.quizzes.generate.code_count') ?></label>
                            <input id="qtaCocorrezione" class="form-control" type="number" min="1" name="qta_cocorrezione" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.quizzes.generate.print_pdf') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
