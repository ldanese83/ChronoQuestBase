<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$userDisplayName = (string) ($userDisplayName ?? '');
$emails = is_array($emails ?? null) ? $emails : [];
$displayName = $userDisplayName !== '' ? $userDisplayName : $translator->translate('testcreator.teacher_emails.admin_fallback');
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.teacher_emails.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.teacher_emails.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?></h5>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTeacherEmailModal"><?= $translator->translate('testcreator.teacher_emails.add_email') ?></button>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importTeacherEmailsModal"><?= $translator->translate('testcreator.teacher_emails.import_csv') ?></button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.teacher_emails.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorTeacherEmailsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.teacher_emails.email') ?></th>
                            <th style="width: 160px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($emails as $email): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($email['mail'] ?? '')) ?></td>
                                <td class="text-center">
                                    <form method="POST" action="/testcreator/mail-docenti/<?= (int) ($email['id_mail_abilitata'] ?? 0) ?>/delete" class="d-inline js-delete-email-form">
                                        <button type="submit" class="btn btn-danger btn-sm" data-email="<?= htmlspecialchars((string) ($email['mail'] ?? ''), ENT_QUOTES) ?>"><?= $translator->translate('testcreator.subjects.delete') ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addTeacherEmailModal" tabindex="-1" aria-labelledby="addTeacherEmailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="/testcreator/mail-docenti/save" id="addTeacherEmailForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTeacherEmailModalLabel"><?= $translator->translate('testcreator.teacher_emails.modal.add_title') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <label for="teacher-email" class="form-label"><?= $translator->translate('testcreator.teacher_emails.teacher_email') ?></label>
                            <input type="email" class="form-control" name="mail" id="teacher-email" maxlength="255" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.subjects.modal.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importTeacherEmailsModal" tabindex="-1" aria-labelledby="importTeacherEmailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="/testcreator/mail-docenti/import-csv" enctype="multipart/form-data" id="importTeacherEmailsForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importTeacherEmailsModalLabel"><?= $translator->translate('testcreator.teacher_emails.modal.import_title') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger mb-3"><strong><?= $translator->translate('testcreator.teacher_emails.warning_title') ?></strong> <?= $translator->translate('testcreator.teacher_emails.import_warning') ?></p>
                            <label for="teacher-emails-csv" class="form-label"><?= $translator->translate('testcreator.teacher_emails.csv_file') ?></label>
                            <input type="file" class="form-control" name="csv_file" id="teacher-emails-csv" accept=".csv,text/csv" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-info"><?= $translator->translate('testcreator.teacher_emails.import') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
