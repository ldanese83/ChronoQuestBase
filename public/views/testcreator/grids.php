<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$grids = is_array($grids ?? null) ? $grids : [];
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.grids.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.grids.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">
                <?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?>
            </h5>
            <a href="/testcreator/griglie/nuova" class="btn btn-success"><?= $translator->translate('testcreator.grids.add_grid') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.grids.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorGridsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.grids.field.name') ?></th>
                            <th style="width: 220px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($grids as $grid): ?>
                            <?php
                            $gridId = (int) ($grid['id_griglia'] ?? 0);
                            $gridName = (string) ($grid['nome_griglia'] ?? '');
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($gridName) ?></td>
                                <td class="text-center text-md-start">
                                    <a href="/testcreator/griglie/<?= $gridId ?>/modifica" class="btn btn-warning btn-sm"><?= $translator->translate('testcreator.subjects.edit') ?></a>
                                    <form method="POST" action="/testcreator/griglie/<?= $gridId ?>/delete" class="d-inline js-delete-grid-form">
                                        <button type="submit" class="btn btn-danger btn-sm" data-grid-name="<?= htmlspecialchars($gridName, ENT_QUOTES) ?>"><?= $translator->translate('testcreator.subjects.delete') ?></button>
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
