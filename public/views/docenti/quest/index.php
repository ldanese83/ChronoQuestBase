<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$quests = $quests ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('quest.active') ?>:
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.classes.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateQuestModal">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('quest.new') ?>
            </button>
            <a href="/docenti/quest/import-export" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-file-import fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('quest.import_export') ?>
            </a>
        </div>

        <div id="quest-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quest</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="questTable" data-page-length="10" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:25%"><?= $translator->translate('quest.name') ?></th>
                                <th style="width:30%"><?= $translator->translate('quest.image') ?></th>
                                <th style="width:15%"><?= $translator->translate('quest.access') ?></th>
                                <th style="width:15%"><?= $translator->translate('common.update') ?></th>
                                <th style="width:15%"><?= $translator->translate('common.delete') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quests as $quest): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($quest['image_quest'])): ?>
                                            <img src="<?= htmlspecialchars((string) $quest['image_quest']) ?>" alt="Immagine quest" style="max-width: 170px; max-height: 90px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('quest.noimage') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                                            <i class="fas fa-pen fa-sm text-white-50 me-1"></i><?= $translator->translate('quest.access') ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm js-edit-quest"
                                            data-quest-id="<?= (int) ($quest['id_quest'] ?? 0) ?>"
                                            data-quest-name="<?= htmlspecialchars((string) ($quest['nome_quest'] ?? ''), ENT_QUOTES) ?>"
                                            data-quest-blocca="<?= (int) ($quest['blocca_ese'] ?? 1) ?>">
                                            <i class="fas fa-pen fa-sm text-white-50 me-1"></i><?= $translator->translate('common.update') ?>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm js-delete-quest"
                                            data-quest-id="<?= (int) ($quest['id_quest'] ?? 0) ?>"
                                            data-quest-name="<?= htmlspecialchars((string) ($quest['nome_quest'] ?? ''), ENT_QUOTES) ?>">
                                            <i class="fas fa-trash fa-sm text-white-50 me-1"></i><?= $translator->translate('common.delete') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="questFormModal" tabindex="-1" role="dialog" aria-labelledby="questFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="questForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="questFormModalLabel"><?= $translator->translate('quest.newinsert') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Chiudi">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="questId" name="id_quest" value="0">

                            <div class="form-group">
                                <label for="questName"><?= $translator->translate('quest.name') ?></label>
                                <input style="width:100%" type="text" class="form-control" id="questName" name="nome_quest" required>
                            </div>

                            <div class="form-group">
                                <label for="questImage"><?= $translator->translate('quest.imageq') ?></label>
                                <input style="width:100%" type="file" class="form-control-file" id="questImage" name="image_quest" accept=".jpg,.jpeg,.png,.gif,.webp">
                                <small class="form-text text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="questMap"><?= $translator->translate('quest.plantq') ?></label>
                                <input style="width:100%" type="file" class="form-control-file" id="questMap" name="piantina_quest" accept=".jpg,.jpeg,.png,.gif,.webp">
                                <small class="form-text text-muted"></code>.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label for="questLockMode"><?= $translator->translate('quest.blockexercise') ?></label>
                                <select style="width:100%" class="form-control" id="questLockMode" name="blocca_ese">
                                    <option value="1" selected><?= $translator->translate('quest.yesblock') ?></option>
                                    <option value="2"><?= $translator->translate('quest.noblock') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveQuestButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
