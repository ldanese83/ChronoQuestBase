<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$sets = $sets ?? [];
$setTypes = $setTypes ?? [];
$personalizationsByType = $personalizationsByType ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.customizations.sets.page_title_class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#setModal" id="openCreateSetModal">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.customizations.sets.button.add_set') ?>
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.sets.import_export') ?></h6>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <form method="GET" action="/docenti/personalizzazioni/set/esporta/0" id="exportSetForm" class="d-flex gap-2">
                        <select class="form-control" id="exportSetSelect" required>
                            <option value=""><?= $translator->translate('teacher.customizations.sets.export.select_set') ?></option>
                            <?php foreach ($sets as $set): ?>
                                <option value="<?= (int) ($set['id_set'] ?? 0) ?>"><?= htmlspecialchars((string) ($set['nome_set'] ?? '')) ?> (<?= $translator->translate('teacher.customizations.type.' . (string) ($set['tipologia'] ?? '')) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary"><?= $translator->translate('teacher.customizations.sets.export_zip') ?></button>
                    </form>
                    <form method="POST" action="/docenti/personalizzazioni/set/importa-file" enctype="multipart/form-data" class="d-flex gap-2">
                        <input type="file" class="form-control" name="set_archive" accept=".zip" required>
                        <button type="submit" class="btn btn-sm btn-info"><?= $translator->translate('teacher.customizations.sets.import_zip') ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.sets.created_section') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="setsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('teacher.customizations.sets.field.name') ?></th>
                            <th><?= $translator->translate('teacher.customizations.sets.field.type') ?></th>
                            <th><?= $translator->translate('teacher.customizations.sets.field.color') ?></th>
                            <th><?= $translator->translate('teacher.customizations.section.list') ?></th>
                            <th><?= $translator->translate('teacher.customizations.field.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sets as $set): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($set['nome_set'] ?? '')) ?></td>
                                <td><?= $translator->translate('teacher.customizations.type.' . (string) ($set['tipologia'] ?? '')) ?></td>
                                <td>
                                    <span class="badge" style="background: <?= htmlspecialchars((string) ($set['colore_set'] ?? '#2f80ed')) ?>; color: #fff;">
                                        <?= htmlspecialchars((string) ($set['colore_set'] ?? '#2f80ed')) ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= (int) ($set['totale_personalizzazioni'] ?? 0) ?></td>
                                <td class="text-center d-flex gap-2 justify-content-center flex-wrap">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning js-edit-set"
                                        data-id="<?= (int) ($set['id_set'] ?? 0) ?>"
                                        data-name="<?= htmlspecialchars((string) ($set['nome_set'] ?? ''), ENT_QUOTES) ?>"
                                        data-type="<?= htmlspecialchars((string) ($set['tipologia'] ?? ''), ENT_QUOTES) ?>"
                                        data-color="<?= htmlspecialchars((string) ($set['colore_set'] ?? '#2f80ed'), ENT_QUOTES) ?>">
                                        <?= $translator->translate('common.edit') ?>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-secondary js-assign-set"
                                        data-id="<?= (int) ($set['id_set'] ?? 0) ?>"
                                        data-name="<?= htmlspecialchars((string) ($set['nome_set'] ?? ''), ENT_QUOTES) ?>"
                                        data-type="<?= htmlspecialchars((string) ($set['tipologia'] ?? ''), ENT_QUOTES) ?>">
                                        <?= $translator->translate('teacher.customizations.section.list') ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <form method="POST" action="/docenti/personalizzazioni/set/save" class="modal fade" id="setModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setModalLabel"><?= $translator->translate('teacher.customizations.sets.button.add_set') ?></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_set" id="setId" value="0">
                        <div class="form-group">
                            <label for="setName"><?= $translator->translate('teacher.customizations.sets.field.name') ?></label>
                            <input type="text" class="form-control" name="nome_set" id="setName" required>
                        </div>
                        <div class="form-group">
                            <label for="setType"><?= $translator->translate('teacher.customizations.sets.field.type') ?></label>
                            <select class="form-control" name="tipologia" id="setType" required>
                                <?php foreach ($setTypes as $type): ?>
                                    <option value="<?= htmlspecialchars((string) $type) ?>"><?= $translator->translate('teacher.customizations.type.' . (string) $type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label for="setColor"><?= $translator->translate('teacher.customizations.sets.field.color') ?></label>
                            <input type="color" class="form-control" name="colore_set" id="setColor" value="#2f80ed" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('common.save') ?></button>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" action="/docenti/personalizzazioni/set/assegna" class="modal fade" id="assignSetModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignSetModalLabel"><?= $translator->translate('teacher.customizations.sets.assign.title') ?></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_set" id="assignSetId" value="0">
                        <p class="mb-2"><?= $translator->translate('teacher.customizations.sets.assign.set_label') ?>: <strong id="assignSetName"></strong> (<span id="assignSetType"></span>)</p>
                        <?php foreach ($setTypes as $type): ?>
                            <div class="assign-type-list" data-type="<?= htmlspecialchars((string) $type) ?>" style="display:none;">
                                <?php foreach (($personalizationsByType[$type] ?? []) as $pers): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input js-set-personalization-checkbox" type="checkbox"
                                               name="personalizzazioni[]"
                                               value="<?= (int) ($pers['id_personalizzazione'] ?? 0) ?>"
                                               data-current-set="<?= (int) ($pers['fk_set'] ?? 0) ?>"
                                               id="pers_<?= (int) ($pers['id_personalizzazione'] ?? 0) ?>">
                                        <label class="form-check-label" for="pers_<?= (int) ($pers['id_personalizzazione'] ?? 0) ?>">
                                            <?= htmlspecialchars((string) ($pers['nome_personalizzazione'] ?? '')) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($personalizationsByType[$type] ?? [])): ?>
                                    <p class="text-muted"><?= $translator->translate('teacher.customizations.sets.assign.none_available') ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.customizations.sets.assign.save') ?></button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
