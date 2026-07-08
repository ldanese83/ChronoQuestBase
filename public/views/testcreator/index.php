<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$subjects = $subjects ?? [];
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.index.title') ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= htmlspecialchars(sprintf($translator->translate('testcreator.index.greeting_subjects'), $displayName)) ?>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorSubjectsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th style="width: 180px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?></td>
                                <td class="text-center">
                                    <form method="POST" action="/testcreator/materie/<?= (int) ($subject['id_materia'] ?? 0) ?>/disassegna" class="d-inline js-unassign-form">
                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            data-subject-name="<?= htmlspecialchars((string) ($subject['nome_materia'] ?? ''), ENT_QUOTES) ?>">
                                            <?= $translator->translate('testcreator.index.unassign') ?>
                                        </button>
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
