<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$subjects = is_array($subjects ?? null) ? $subjects : [];
$topics = is_array($topics ?? null) ? $topics : [];
$selectedSubjectId = (int) ($selectedSubjectId ?? 0);
$userDisplayName = (string) ($userDisplayName ?? '');
$isAdmin = (bool) ($isAdmin ?? false);
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.topics.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.topics.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">
                <?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?>
            </h5>
            <button
                type="button"
                class="btn btn-success js-open-topic-modal"
                data-topic-id="0"
                data-topic-name=""
                data-topic-subject-id="<?= $selectedSubjectId ?>"
                data-toggle="modal"
                data-target="#topicModal">
                <?= $translator->translate('testcreator.topics.add_topic') ?>
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.topics.card_title') ?></h6>
                <div class="d-flex align-items-center gap-2">
                    <label for="subjectFilter" class="mb-0 mr-2"><?= $translator->translate('testcreator.topics.subject_filter') ?></label>
                    <select id="subjectFilter" class="form-control" style="min-width: 240px;">
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
                    <table class="table table-bordered" id="testCreatorTopicsTable" width="100%" cellspacing="0" data-initial-subject-id="<?= $selectedSubjectId ?>">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.topics.subject_id') ?></th>
                            <th><?= $translator->translate('testcreator.topics.topic') ?></th>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th style="width: 320px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topics as $topic): ?>
                            <?php
                            $topicId = (int) ($topic['id_argomento'] ?? 0);
                            $topicName = (string) ($topic['nome_argomento'] ?? '');
                            $subjectId = (int) ($topic['fk_materia'] ?? 0);
                            $subjectName = (string) ($topic['nome_materia'] ?? '');
                            ?>
                            <tr>
                                <td><?= $subjectId ?></td>
                                <td><?= htmlspecialchars($topicName) ?></td>
                                <td><?= htmlspecialchars($subjectName) ?></td>
                                <td class="text-center text-md-start">
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm js-open-topic-modal"
                                        data-topic-id="<?= $topicId ?>"
                                        data-topic-name="<?= htmlspecialchars($topicName, ENT_QUOTES) ?>"
                                        data-topic-subject-id="<?= $subjectId ?>"
                                        data-toggle="modal"
                                        data-target="#topicModal">
                                        <?= $translator->translate('testcreator.subjects.edit') ?>
                                    </button>

                                    <?php if ($isAdmin): ?>
                                        <form method="POST" action="/testcreator/argomenti/<?= $topicId ?>/delete" class="d-inline js-delete-topic-form">
                                            <input type="hidden" name="fk_materia" value="<?= $subjectId ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" data-topic-name="<?= htmlspecialchars($topicName, ENT_QUOTES) ?>">
                                                <?= $translator->translate('testcreator.subjects.delete') ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="topicModal" tabindex="-1" aria-labelledby="topicModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="/testcreator/argomenti/save" id="topicSaveForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="topicModalLabel"><?= $translator->translate('testcreator.topics.modal.edit_title') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_argomento" id="topic-id" value="0">
                            <div class="mb-3">
                                <label for="topic-subject" class="form-label"><?= $translator->translate('testcreator.index.subject') ?></label>
                                <select class="form-control" id="topic-subject" name="fk_materia" required>
                                    <option value=""><?= $translator->translate('testcreator.topics.select_subject') ?></option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= (int) ($subject['id_materia'] ?? 0) ?>">
                                            <?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="topic-name" class="form-label"><?= $translator->translate('testcreator.topics.field.name') ?></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="topic-name"
                                    name="nome_argomento"
                                    maxlength="255"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.subjects.modal.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
