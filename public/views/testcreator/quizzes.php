<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quizzes = is_array($quizzes ?? null) ? $quizzes : [];
$userDisplayName = (string) ($userDisplayName ?? '');
$displayName = $userDisplayName !== '' ? $userDisplayName : $translator->translate('testcreator.index.teacher_fallback');
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <div class="container" style="background-color: #073822;">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-2 text-center mb-3 mb-sm-0">
                        <img src="/assets/images/cronoquest_verde.png" alt="<?= htmlspecialchars($translator->translate('testcreator.index.logo_alt')) ?>" class="img-fluid" style="max-height: 90px;" />
                    </div>
                    <div class="col-12 col-sm-10 text-sm-start text-center">
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.quizzes.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.quizzes.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?></h5>
            <a href="/testcreator/quiz/nuovo" class="btn btn-success"><?= $translator->translate('testcreator.quizzes.create') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.quizzes.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorQuizzesTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.quizzes.field.name') ?></th>
                            <th><?= $translator->translate('testcreator.quizzes.question_count') ?></th>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th><?= $translator->translate('testcreator.quizzes.created_at') ?></th>
                            <th style="width: 290px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($quizzes as $quiz): ?>
                            <?php $quizId = (int) ($quiz['id_quiz'] ?? 0); ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($quiz['nome_quiz'] ?? '')) ?></td>
                                <td><?= (int) ($quiz['tot_domande'] ?? 0) ?></td>
                                <td><?= htmlspecialchars((string) ($quiz['nome_materia'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($quiz['data_creazione'] ?? '')) ?></td>
                                <td>
                                    <a href="/testcreator/quiz/<?= $quizId ?>/genera" class="btn btn-primary btn-sm"><?= $translator->translate('testcreator.quizzes.generate') ?></a>
                                    <a href="/testcreator/quiz/<?= $quizId ?>/modifica" class="btn btn-warning btn-sm"><?= $translator->translate('testcreator.subjects.edit') ?></a>
                                    <form method="POST" action="/testcreator/quiz/<?= $quizId ?>/delete" class="d-inline js-delete-quiz-form">
                                        <button type="submit" class="btn btn-danger btn-sm" data-quiz-name="<?= htmlspecialchars((string) ($quiz['nome_quiz'] ?? ''), ENT_QUOTES) ?>"><?= $translator->translate('testcreator.subjects.delete') ?></button>
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
