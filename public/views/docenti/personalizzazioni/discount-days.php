<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$discountDays = $discountDays ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.customizations.discount.page_title') ?></h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.discount.new_section') ?></h6></div>
            <div class="card-body">
                <form method="POST" action="/docenti/personalizzazioni/giornate-sconti/save">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="data_sconto"><?= $translator->translate('teacher.customizations.discount.field.date') ?></label>
                            <input type="date" class="form-control" id="data_sconto" name="data_sconto" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="percentuale_sconto"><?= $translator->translate('teacher.customizations.discount.field.percentage') ?></label>
                            <input type="number" class="form-control" id="percentuale_sconto" name="percentuale_sconto" min="1" max="100" required>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sconto_ricorrente" name="sconto_ricorrente" value="1">
                                <label class="form-check-label" for="sconto_ricorrente"><?= $translator->translate('teacher.customizations.discount.field.recurrent_yearly') ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="motivazione_sconto"><?= $translator->translate('teacher.customizations.discount.field.reason') ?></label>
                        <textarea class="form-control" id="motivazione_sconto" name="motivazione_sconto" rows="6" aria-required="true"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.customizations.discount.button.save') ?></button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.discount.configured_section') ?></h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="discountDaysTable" width="100%" cellspacing="0">
                        <thead>
                        <tr><th><?= $translator->translate('teacher.customizations.discount.field.date_short') ?></th><th><?= $translator->translate('teacher.customizations.discount.field.recurrent') ?></th><th><?= $translator->translate('teacher.customizations.discount.field.discount') ?></th><th><?= $translator->translate('teacher.customizations.discount.field.reason') ?></th><th><?= $translator->translate('teacher.customizations.field.actions') ?></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($discountDays as $day): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) date('d/m/Y', strtotime((string) ($day['data'] ?? 'now')))) ?></td>
                                <td><?= ((int) ($day['recurrent'] ?? 0) === 1) ? $translator->translate('common.yes') : $translator->translate('common.no') ?></td>
                                <td><?= (int) ($day['sconto'] ?? 0) ?>%</td>
                                <td><div><?= html_entity_decode((string) ($day['motivazione'] ?? '')) ?></div></td>
                                <td class="text-center">
                                    <form method="POST" action="/docenti/personalizzazioni/giornate-sconti/<?= (int) ($day['id_giornata'] ?? 0) ?>/delete" onsubmit="return confirm(window.cqT('teacher.customizations.discount.delete.confirm', 'Eliminare questa giornata sconti?'));">
                                        <button type="submit" class="btn btn-sm btn-danger"><?= $translator->translate('common.delete') ?></button>
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
