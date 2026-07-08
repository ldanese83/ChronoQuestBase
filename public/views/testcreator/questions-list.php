<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$topic = is_array($topic ?? null) ? $topic : null;
$questions = is_array($questions ?? null) ? $questions : [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $topic !== null): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;">
                <?= $translator->translate('testcreator.questions.list.title') ?> - <?= htmlspecialchars((string) ($topic['nome_materia'] ?? '')) ?> / <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?>
            </h3>
        </div>

        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="/testcreator/domande" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
            <a href="/testcreator/domande/argomenti/<?= (int) ($topic['id_argomento'] ?? 0) ?>/nuova" class="btn btn-success btn-sm"><?= $translator->translate('testcreator.questions.list.add_question') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.questions.list.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorQuestionsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.questions.list.question') ?></th>
                            <th><?= $translator->translate('testcreator.questions.list.type') ?></th>
                            <th><?= $translator->translate('testcreator.questions.list.book') ?></th>
                            <th><?= $translator->translate('testcreator.questions.list.group') ?></th>
                            <th style="width: 260px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($questions as $question): ?>
                            <?php
                            $questionId = (int) ($question['id_domanda'] ?? 0);
                            $typeText = $useEnglishDbTranslations && trim((string) ($question['tipo_en'] ?? '')) !== '' ? (string) $question['tipo_en'] : (string) ($question['tipo'] ?? '');
                            $bookText = trim(((string) ($question['titolo_libro'] ?? '')) . ' - ' . ((string) ($question['autori'] ?? '')) . ' - ' . ((string) ($question['casa_editrice'] ?? '')));
                            ?>
                            <tr>
                                <td><?= html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($typeText) ?></td>
                                <td><?= htmlspecialchars($bookText) ?></td>
                                <td><?= (int) ($question['num_gruppo'] ?? 0) > 0 ? (int) $question['num_gruppo'] : $translator->translate('testcreator.questions.list.no_group') ?></td>
                                <td>
                                    <a href="/testcreator/domande/<?= $questionId ?>/modifica" class="btn btn-warning btn-sm"><?= $translator->translate('testcreator.subjects.edit') ?></a>
                                    <form method="POST" action="/testcreator/domande/<?= $questionId ?>/remove" class="d-inline js-remove-question-form">
                                        <input type="hidden" name="topic_id" value="<?= (int) ($topic['id_argomento'] ?? 0) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"><?= $translator->translate('testcreator.subjects.delete') ?></button>
                                    </form>
                                    <form method="POST" action="/testcreator/domande/<?= $questionId ?>/remove-permanent" class="d-inline js-remove-permanent-question-form">
                                        <input type="hidden" name="topic_id" value="<?= (int) ($topic['id_argomento'] ?? 0) ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm"><?= $translator->translate('testcreator.questions.list.delete_permanent') ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
