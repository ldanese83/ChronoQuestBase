<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$powers = $powers ?? [];
$importablePowers = $importablePowers ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.powers.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.powers.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" id="openImportPowerModal">
                    <i class="fas fa-file-import fa-sm me-1"></i>
                    <?= $translator->translate('teacher.powers.button.import_from_classes') ?>
                </button>
                <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreatePowerModal">
                    <i class="fas fa-bolt fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.powers.button.add') ?>
                </button>
            </div>
        </div>

        <div id="power-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.powers.section.available') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="powerTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width:16%"><?= $translator->translate('teacher.powers.field.name') ?></th>
                            <th style="width:24%"><?= $translator->translate('teacher.powers.field.description') ?></th>
                            <th style="width:14%"><?= $translator->translate('teacher.powers.field.image') ?></th>
                            <th style="width:8%"><?= $translator->translate('teacher.powers.field.level') ?></th>
                            <th style="width:8%"><?= $translator->translate('teacher.powers.field.mana') ?></th>
                            <th style="width:10%"><?= $translator->translate('teacher.powers.field.type') ?></th>
                            <th style="width:20%"><?= $translator->translate('teacher.powers.field.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($powers as $power): ?>
                            <?php
                            $isFixed = (int) ($power['fisso'] ?? 0) === 1;
                            $translatedPowerName = trim((string) ($power['nome_potere_en'] ?? ''));
                            $translatedPowerDescription = trim((string) ($power['descrizione_potere_en'] ?? ''));
                            $displayPowerName = $useEnglishDbTranslations && $translatedPowerName !== ''
                                ? $translatedPowerName
                                : (string) ($power['nome_potere'] ?? '');
                            $displayPowerDescription = $useEnglishDbTranslations && $translatedPowerDescription !== ''
                                ? $translatedPowerDescription
                                : (string) ($power['descrizione_potere'] ?? '');
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($displayPowerName) ?></td>
                                <td><?= htmlspecialchars_decode($displayPowerDescription, ENT_QUOTES) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($power['img_potere'])): ?>
                                        <img src="<?= htmlspecialchars((string) $power['img_potere']) ?>" alt="Potere" style="max-width: 100px; max-height: 100px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                                    <?php else: ?>
                                        <span class="text-muted"><?= $translator->translate('common.not_available.short') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= (int) ($power['livello'] ?? 0) ?></td>
                                <td class="text-center"><?= (int) ($power['mana_necessario'] ?? 0) ?></td>
                                <td class="text-center">
                                    <?php if ($isFixed): ?>
                                        <span class="badge bg-secondary"><?= $translator->translate('teacher.powers.type.fixed') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $translator->translate('teacher.powers.type.class') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($isFixed): ?>
                                        <span class="text-muted"><?= $translator->translate('teacher.powers.read_only') ?></span>
                                    <?php else: ?>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning js-edit-power"
                                                data-power-id="<?= (int) ($power['id_potere'] ?? 0) ?>"
                                                data-power-name="<?= htmlspecialchars((string) ($power['nome_potere'] ?? ''), ENT_QUOTES) ?>"
                                                data-power-description="<?= htmlspecialchars_decode((string) ($power['descrizione_potere'] ?? ''), ENT_QUOTES) ?>"
                                                data-power-level="<?= (int) ($power['livello'] ?? 0) ?>"
                                                data-power-mana="<?= (int) ($power['mana_necessario'] ?? 1) ?>">
                                                <?= $translator->translate('common.edit') ?>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger js-delete-power"
                                                data-power-id="<?= (int) ($power['id_potere'] ?? 0) ?>"
                                                data-power-name="<?= htmlspecialchars((string) ($power['nome_potere'] ?? ''), ENT_QUOTES) ?>">
                                                <?= $translator->translate('common.delete') ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="powerFormModal" tabindex="-1" role="dialog" aria-labelledby="powerFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="powerForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="powerFormModalLabel"><?= $translator->translate('teacher.powers.button.add') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="powerId" name="id_potere" value="0">

                            <div class="form-group">
                                <label for="powerName"><?= $translator->translate('teacher.powers.field.name') ?></label>
                                <input type="text" class="form-control" id="powerName" name="nome_potere" required>
                            </div>

                            <div class="form-group">
                                <label for="powerDescription"><?= $translator->translate('teacher.powers.field.description') ?></label>
                                <textarea class="form-control" id="powerDescription" name="descrizione_potere" rows="4" required></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="powerLevel"><?= $translator->translate('teacher.powers.field.level') ?></label>
                                    <input type="number" min="0" class="form-control" id="powerLevel" name="livello" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="power<?= $translator->translate('teacher.powers.field.mana') ?>"><?= $translator->translate('teacher.powers.field.mana') ?> consumato</label>
                                    <input type="number" min="1" class="form-control" id="power<?= $translator->translate('teacher.powers.field.mana') ?>" name="mana_pot" required>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="powerImage"><?= $translator->translate('teacher.powers.field.image') ?> potere</label>
                                <input type="file" class="form-control-file" id="powerImage" name="power_image" accept=".png,.jpg,.jpeg,.gif,.webp">
                                <small class="text-muted"><?= $translator->translate('teacher.powers.image.keep_current_hint') ?></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                            <button type="submit" class="btn btn-primary" id="savePowerButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importPowerModal" tabindex="-1" role="dialog" aria-labelledby="importPowerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importPowerModalLabel"><?= $translator->translate('teacher.powers.modal.import_title') ?></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if ($importablePowers === []): ?>
                            <div class="alert alert-info mb-0"><?= $translator->translate('teacher.powers.import.none_available') ?></div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="powerImportTable" data-page-length="10" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><?= $translator->translate('common.name') ?></th>
                                        <th><?= $translator->translate('teacher.powers.field.description') ?></th>
                                        <th><?= $translator->translate('teacher.powers.field.source_class') ?></th>
                                        <th><?= $translator->translate('teacher.powers.label.year') ?></th>
                                        <th><?= $translator->translate('teacher.powers.field.level') ?></th>
                                        <th><?= $translator->translate('teacher.powers.field.mana') ?></th>
                                        <th><?= $translator->translate('common.import') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($importablePowers as $importPower): ?>
                                        <tr>
                                            <td><?= htmlspecialchars((string) ($importPower['nome_potere'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars_decode((string) ($importPower['descrizione_potere'] ?? ''), ENT_QUOTES) ?></td>
                                            <td><?= htmlspecialchars((string) ($importPower['nome_classe'] ?? '-')) ?></td>
                                            <td><?= htmlspecialchars((string) ($importPower['anno_scolastico'] ?? '-')) ?></td>
                                            <td class="text-center"><?= (int) ($importPower['livello'] ?? 0) ?></td>
                                            <td class="text-center"><?= (int) ($importPower['mana_necessario'] ?? 0) ?></td>
                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-info js-import-power"
                                                    data-source-power-id="<?= (int) ($importPower['id_potere'] ?? 0) ?>"
                                                    data-source-power-name="<?= htmlspecialchars((string) ($importPower['nome_potere'] ?? ''), ENT_QUOTES) ?>">
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
