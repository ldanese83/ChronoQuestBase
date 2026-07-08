<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$punishments = $punishments ?? [];
$importablePunishments = $importablePunishments ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.punishments.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.punishments.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" id="openImportPunishmentModal">
                    <i class="fas fa-file-import fa-sm me-1"></i>
                    <?= $translator->translate('teacher.punishments.button.import_from_classes') ?>
                </button>
                <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreatePunishmentModal">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.punishments.button.add') ?>
                </button>
            </div>
        </div>

        <div id="punishment-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.punishments.section.available') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="punishmentTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width:45%"><?= $translator->translate('teacher.punishments.field.description') ?></th>
                            <th style="width:20%"><?= $translator->translate('teacher.punishments.field.image') ?></th>
                            <th style="width:10%"><?= $translator->translate('teacher.punishments.field.delivery_days') ?></th>
                            <th style="width:25%"><?= $translator->translate('teacher.punishments.field.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($punishments as $punishment): ?>
                            <tr>
                                <td><?= htmlspecialchars_decode((string) ($punishment['descrizione_punizione'] ?? ''), ENT_QUOTES) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($punishment['img_punizione'])): ?>
                                        <img src="<?= htmlspecialchars((string) $punishment['img_punizione']) ?>" alt="<?= htmlspecialchars($translator->translate('teacher.punishments.alt.punishment')) ?>" style="max-width: 100px; max-height: 100px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                                    <?php else: ?>
                                        <span class="text-muted"><?= $translator->translate('common.not_available.short') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= (int) ($punishment['giorni_per_consegna'] ?? 0) ?></td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning js-edit-punishment"
                                            data-punishment-id="<?= (int) ($punishment['id_punizione'] ?? 0) ?>"
                                            data-punishment-description="<?= htmlspecialchars((string) htmlspecialchars_decode((string) ($punishment['descrizione_punizione'] ?? ''), ENT_QUOTES), ENT_QUOTES) ?>"
                                            data-punishment-days="<?= (int) ($punishment['giorni_per_consegna'] ?? 1) ?>">
                                            <?= $translator->translate('common.edit') ?>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger js-delete-punishment"
                                            data-punishment-id="<?= (int) ($punishment['id_punizione'] ?? 0) ?>">
                                            <?= $translator->translate('common.delete') ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="punishmentFormModal" tabindex="-1" role="dialog" aria-labelledby="punishmentFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="punishmentForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="punishmentFormModalLabel"><?= $translator->translate('teacher.punishments.button.add') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="punishmentId" name="id_punizione" value="0">

                            <div class="form-group">
                                <label for="punishmentDescription"><?= $translator->translate('teacher.punishments.field.description') ?></label>
                                <textarea class="form-control" id="punishmentDescription" name="descrizione_punizione" rows="5" required></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="punishmentDays"><?= $translator->translate('teacher.punishments.field.delivery_days') ?></label>
                                    <input type="number" min="1" class="form-control" id="punishmentDays" name="giorni_per_consegna" required>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="punishmentImage"><?= $translator->translate('teacher.punishments.field.punishment_image') ?></label>
                                <input type="file" class="form-control-file" id="punishmentImage" name="punishment_image" accept=".png,.jpg,.jpeg,.gif,.webp">
                                <small class="text-muted"><?= $translator->translate('teacher.punishments.image.keep_current_hint') ?></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                            <button type="submit" class="btn btn-primary" id="savePunishmentButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importPunishmentModal" tabindex="-1" role="dialog" aria-labelledby="importPunishmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importPunishmentModalLabel"><?= $translator->translate('teacher.punishments.modal.import_title') ?></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if ($importablePunishments === []): ?>
                            <div class="alert alert-info mb-0"><?= $translator->translate('teacher.punishments.import.none_available') ?></div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="punishmentImportTable" data-page-length="10" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><?= $translator->translate('teacher.punishments.field.description') ?></th>
                                        <th><?= $translator->translate('teacher.punishments.field.image') ?></th>
                                        <th><?= $translator->translate('teacher.punishments.field.days') ?></th>
                                        <th><?= $translator->translate('teacher.punishments.field.source_class') ?></th>
                                        <th><?= $translator->translate('teacher.punishments.label.year') ?></th>
                                        <th><?= $translator->translate('common.import') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($importablePunishments as $importPunishment): ?>
                                        <tr>
                                            <td><?= htmlspecialchars_decode((string) ($importPunishment['descrizione_punizione'] ?? ''), ENT_QUOTES) ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($importPunishment['img_punizione'])): ?>
                                                    <img src="<?= htmlspecialchars((string) $importPunishment['img_punizione']) ?>" alt="<?= htmlspecialchars($translator->translate('teacher.punishments.alt.punishment')) ?>" style="max-width: 80px; max-height: 80px;">
                                                <?php else: ?>
                                                    <span class="text-muted"><?= $translator->translate('common.not_available.short') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?= (int) ($importPunishment['giorni_per_consegna'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars((string) ($importPunishment['nome_classe'] ?? '-')) ?></td>
                                            <td><?= htmlspecialchars((string) ($importPunishment['anno_scolastico'] ?? '-')) ?></td>
                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-info js-import-punishment"
                                                    data-source-punishment-id="<?= (int) ($importPunishment['id_punizione'] ?? 0) ?>">
                                                    <i class="fas fa-download me-1"></i>
                                                    <?= $translator->translate('common.import') ?>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
