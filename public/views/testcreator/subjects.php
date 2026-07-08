<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$subjects = is_array($subjects ?? null) ? $subjects : [];
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.subjects.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.subjects.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">
                <?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?>
            </h5>
            <div class="d-flex gap-2 flex-wrap">
                <?php if ($isAdmin): ?>
                    <form method="POST" action="/testcreator/materie/import-json" enctype="multipart/form-data" class="d-flex gap-2">
                        <input type="file" class="form-control form-control-sm" name="subject_json" accept=".json,application/json" required>
                        <button type="submit" class="btn btn-outline-primary btn-sm"><?= $translator->translate('testcreator.subjects.import_json') ?></button>
                    </form>
                    <button
                        type="button"
                        class="btn btn-success js-open-subject-modal"
                        data-subject-id="0"
                        data-toggle="modal"
                        data-target="#subjectModal">
                        <?= $translator->translate('testcreator.subjects.add_subject') ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.subjects.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorAllSubjectsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th><?= $translator->translate('testcreator.subjects.status') ?></th>
                            <th style="width: 420px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($subjects as $subject): ?>
                            <?php
                            $subjectId = (int) ($subject['id_materia'] ?? 0);
                            $subjectName = (string) ($subject['nome_materia'] ?? '');
                            $assigned = ((int) ($subject['assegnata'] ?? 0)) === 1;
                            ?>
                            <tr class="<?= $assigned ? 'table-success' : '' ?>">
                                <td><?= htmlspecialchars($subjectName) ?></td>
                                <td>
                                    <?php if ($assigned): ?>
                                        <span class="badge bg-success"><?= $translator->translate('testcreator.subjects.assigned_to_you') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= $translator->translate('testcreator.subjects.not_assigned') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-md-start">
                                    <?php if ($assigned): ?>
                                        <form method="POST" action="/testcreator/materie/<?= $subjectId ?>/disassegna" class="d-inline js-unassign-form">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" data-subject-name="<?= htmlspecialchars($subjectName, ENT_QUOTES) ?>">
                                                <?= $translator->translate('testcreator.subjects.unassign_me') ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="/testcreator/materie/<?= $subjectId ?>/assegna" class="d-inline">
                                            <button type="submit" class="btn btn-primary btn-sm"><?= $translator->translate('testcreator.subjects.assign_me') ?></button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($isAdmin): ?>
                                        <a href="/testcreator/materie/<?= $subjectId ?>/export-json" class="btn btn-outline-info btn-sm">
                                            <?= $translator->translate('testcreator.subjects.export_json') ?>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-warning btn-sm js-open-subject-modal"
                                            data-subject-id="<?= $subjectId ?>"
                                            data-toggle="modal"
                                            data-target="#subjectModal">
                                            <?= $translator->translate('testcreator.subjects.edit') ?>
                                        </button>

                                        <form method="POST" action="/testcreator/materie/<?= $subjectId ?>/delete" class="d-inline js-delete-subject-form">
                                            <button type="submit" class="btn btn-danger btn-sm" data-subject-name="<?= htmlspecialchars($subjectName, ENT_QUOTES) ?>">
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

        <?php if ($isAdmin): ?>
            <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="/testcreator/materie/save" id="subjectSaveForm">
                            <div class="modal-header">
                                <h5 class="modal-title" id="subjectModalLabel"><?= $translator->translate('testcreator.subjects.modal.edit_title') ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_materia" id="subject-id" value="0">
                                <div class="mb-3">
                                    <label for="subject-name" class="form-label"><?= $translator->translate('testcreator.subjects.field.name') ?></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="subject-name"
                                        name="nome_materia"
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
    <?php endif; ?>
</div>
