<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$subjects = is_array($subjects ?? null) ? $subjects : [];
$topics = is_array($topics ?? null) ? $topics : [];
$selectedSubjectId = (int) ($selectedSubjectId ?? 0);
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.questions.topics.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.questions.topics.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?></h5>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.questions.topics.card_title') ?></h6>
                <div class="d-flex align-items-center gap-2">
                    <label for="questionSubjectFilter" class="mb-0 mr-2"><?= $translator->translate('testcreator.topics.subject_filter') ?></label>
                    <select id="questionSubjectFilter" class="form-control" style="min-width: 240px;">
                        <option value="0"><?= $translator->translate('testcreator.topics.select_subject') ?></option>
                        <?php foreach ($subjects as $subject): ?>
                            <?php $subjectId = (int) ($subject['id_materia'] ?? 0); ?>
                            <option value="<?= $subjectId ?>" <?= $subjectId === $selectedSubjectId ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorQuestionTopicsTable" width="100%" cellspacing="0" data-initial-subject-id="<?= $selectedSubjectId ?>">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.topics.subject_id') ?></th>
                            <th><?= $translator->translate('testcreator.topics.topic') ?></th>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th style="width: 200px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topics as $topic): ?>
                            <?php $topicId = (int) ($topic['id_argomento'] ?? 0); ?>
                            <tr>
                                <td><?= (int) ($topic['fk_materia'] ?? 0) ?></td>
                                <td><?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($topic['nome_materia'] ?? '')) ?></td>
                                <td>
                                    <a href="/testcreator/domande/argomenti/<?= $topicId ?>" class="btn btn-primary btn-sm"><?= $translator->translate('testcreator.questions.topics.view_questions') ?></a>
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
