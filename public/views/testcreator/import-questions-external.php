<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$topic = is_array($topic ?? null) ? $topic : null;
$importableQuestions = is_array($importableQuestions ?? null) ? $importableQuestions : [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $topic !== null): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.import_questions.external.title') ?></h3>
            <small class="text-white">
                <?= $translator->translate('testcreator.topics.topic') ?>: <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?>
            </small>
        </div>

        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="/testcreator/import-domande" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.import_questions.external.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorImportExternalTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.questions.list.question') ?></th>
                            <th><?= $translator->translate('testcreator.questions.list.type') ?></th>
                            <th><?= $translator->translate('testcreator.questions.list.book') ?></th>
                            <th><?= $translator->translate('testcreator.administrators.teacher') ?></th>
                            <th style="width: 180px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($importableQuestions as $question): ?>
                            <?php
                            $questionId = (int) ($question['id_domanda'] ?? 0);
                            $typeText = $useEnglishDbTranslations && trim((string) ($question['tipo_en'] ?? '')) !== '' ? (string) $question['tipo_en'] : (string) ($question['tipo'] ?? '');
                            $bookText = trim(((string) ($question['titolo_libro'] ?? '')) . ' - ' . ((string) ($question['autori'] ?? '')) . ' - ' . ((string) ($question['casa_editrice'] ?? '')));
                            $isChoiceType = in_array((int) ($question['fk_tipo_domanda'] ?? 0), [2, 3], true);
                            ?>
                            <tr>
                                <td><?= html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($typeText) ?></td>
                                <td><?= htmlspecialchars($bookText) ?></td>
                                <td><?= htmlspecialchars((string) ($question['autore'] ?? '')) ?></td>
                                <td>
                                    <form method="POST" action="/testcreator/import-domande/<?= $questionId ?>/importa" class="d-inline js-import-question-form">
                                        <button type="submit" class="btn btn-info btn-sm"><?= $translator->translate('testcreator.teacher_emails.import') ?></button>
                                    </form>
                                    <?php if ($isChoiceType): ?>
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm js-open-answers-modal"
                                            data-question-id="<?= $questionId ?>"
                                            data-toggle="modal"
                                            data-target="#importAnswersModal">
                                            <?= $translator->translate('testcreator.import_questions.external.view_answers') ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importAnswersModal" tabindex="-1" aria-labelledby="importAnswersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importAnswersModalLabel"><?= $translator->translate('testcreator.import_questions.external.answers_title') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th><?= $translator->translate('testcreator.import_questions.external.answer') ?></th>
                                <th style="width: 110px;"><?= $translator->translate('testcreator.import_questions.external.correct') ?></th>
                            </tr>
                            </thead>
                            <tbody id="importAnswersRows"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
