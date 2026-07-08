<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$grid = is_array($grid ?? null) ? $grid : [];
$gridId = (int) ($grid['id_griglia'] ?? 0);
$isEdit = $gridId > 0;
$formAction = $isEdit ? '/testcreator/griglie/' . $gridId . '/update' : '/testcreator/griglie/save';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $isEdit ? $translator->translate('testcreator.grids.form.edit_title') : $translator->translate('testcreator.grids.form.new_title') ?></h3>
        </div>

        <div class="mb-3"><a href="/testcreator/griglie" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a></div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="<?= htmlspecialchars($formAction) ?>" id="gridForm">
                    <div class="form-group mb-3">
                        <label for="gridName"><?= $translator->translate('testcreator.grids.field.name') ?></label>
                        <input type="text" class="form-control" id="gridName" name="nome_griglia" maxlength="40" required value="<?= htmlspecialchars((string) ($grid['nome_griglia'] ?? '')) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="gridEditor"><?= $translator->translate('testcreator.grids.form.content') ?></label>
                        <textarea id="gridEditor" name="griglia" class="form-control" rows="18" required><?= htmlspecialchars((string) ($grid['griglia'] ?? '')) ?></textarea>
                        <small class="form-text text-muted"><?= $translator->translate('testcreator.grids.form.tinymce_note') ?></small>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.grids.form.save') ?></button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
