<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$userDisplayName = (string) ($userDisplayName ?? '');
$teachers = is_array($teachers ?? null) ? $teachers : [];
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.administrators.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.administrators.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h5 class="mb-0"><?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?></h5>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.administrators.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorAdministratorsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.administrators.first_name') ?></th>
                            <th><?= $translator->translate('testcreator.administrators.last_name') ?></th>
                            <th>Username</th>
                            <th><?= $translator->translate('testcreator.subjects.status') ?></th>
                            <th style="width: 230px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <?php
                            $teacherId = (int) ($teacher['id_utente'] ?? 0);
                            $isAdminTeacher = ((int) ($teacher['is_admin'] ?? 0)) === 1;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($teacher['nome'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($teacher['cognome'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($teacher['username'] ?? '')) ?></td>
                                <td>
                                    <?php if ($isAdminTeacher): ?>
                                        <span class="badge bg-success"><?= $translator->translate('testcreator.administrators.admin') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= $translator->translate('testcreator.administrators.teacher') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-md-start">
                                    <?php if (!$isAdminTeacher): ?>
                                        <form method="POST" action="/testcreator/amministratori/<?= $teacherId ?>/promuovi" class="d-inline js-promote-admin-form">
                                            <button type="submit" class="btn btn-primary btn-sm" data-user="<?= htmlspecialchars((string) ($teacher['username'] ?? ''), ENT_QUOTES) ?>">
                                                <?= $translator->translate('testcreator.administrators.promote') ?>
                                            </button>
                                        </form>
                                    <?php elseif ($teacherId !== 1): ?>
                                        <form method="POST" action="/testcreator/amministratori/<?= $teacherId ?>/rimuovi" class="d-inline js-remove-admin-form">
                                            <button type="submit" class="btn btn-danger btn-sm" data-user="<?= htmlspecialchars((string) ($teacher['username'] ?? ''), ENT_QUOTES) ?>">
                                                <?= $translator->translate('testcreator.administrators.remove_privileges') ?>
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
    <?php endif; ?>
</div>
