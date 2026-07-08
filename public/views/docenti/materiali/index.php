<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$topics = $topics ?? [];
$materials = $materials ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.materials.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.materials.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateMaterialModal">
                <i class="fas fa-file-upload fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('teacher.materials.button.upload') ?>
            </button>
        </div>

        <div id="material-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.materials.section.uploaded') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="materialTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width:20%"><?= $translator->translate('teacher.materials.field.name') ?></th>
                            <th style="width:24%"><?= $translator->translate('teacher.materials.field.description') ?></th>
                            <th style="width:18%"><?= $translator->translate('teacher.materials.field.subject') ?></th>
                            <th style="width:18%"><?= $translator->translate('teacher.materials.field.topic') ?></th>
                            <th style="width:20%"><?= $translator->translate('teacher.materials.field.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($materials as $material): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($material['link_materiale'])): ?>
                                        <a href="<?= htmlspecialchars((string) $material['link_materiale']) ?>" target="_blank" rel="noopener">
                                            <?= htmlspecialchars((string) ($material['nome_materiale'] ?? '')) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars((string) ($material['nome_materiale'] ?? '')) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars_decode((string) ($material['descrizione'] ?? ''), ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars((string) ($material['nome_materia'] ?? '-')) ?></td>
                                <td><?= htmlspecialchars((string) ($material['nome_argomento'] ?? '-')) ?></td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning js-edit-material"
                                            data-material-id="<?= (int) ($material['id_materiale'] ?? 0) ?>"
                                            data-material-name="<?= htmlspecialchars((string) ($material['nome_materiale'] ?? ''), ENT_QUOTES) ?>"
                                            data-material-description="<?= htmlspecialchars_decode((string) ($material['descrizione'] ?? ''), ENT_QUOTES) ?>"
                                            data-material-topic-id="<?= (int) ($material['fk_argomento'] ?? 0) ?>">
                                            <?= $translator->translate('common.edit') ?>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger js-delete-material"
                                            data-material-id="<?= (int) ($material['id_materiale'] ?? 0) ?>"
                                            data-material-name="<?= htmlspecialchars((string) ($material['nome_materiale'] ?? ''), ENT_QUOTES) ?>">
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

        <div class="modal fade" id="materialFormModal" tabindex="-1" role="dialog" aria-labelledby="materialFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="materialForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="materialFormModalLabel"><?= $translator->translate('teacher.materials.modal.add_title') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="materialId" name="id_materiale" value="0">

                            <div class="form-group">
                                <label for="materialName"><?= $translator->translate('teacher.materials.field.material_name') ?></label>
                                <input type="text" class="form-control" id="materialName" name="nome_materiale" required>
                            </div>

                            <div class="form-group">
                                <label for="materialTopic"><?= $translator->translate('teacher.materials.field.topic') ?></label>
                                <select class="form-control" id="materialTopic" name="id_argomento" required>
                                    <option value=""><?= $translator->translate('teacher.materials.topic.select') ?></option>
                                    <?php foreach ($topics as $topic): ?>
                                        <option value="<?= (int) ($topic['id_argomento'] ?? 0) ?>">
                                            <?= htmlspecialchars((string) ($topic['nome_materia'] ?? '')) ?> · <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="materialDescription"><?= $translator->translate('teacher.materials.field.description') ?></label>
                                <textarea class="form-control" id="materialDescription" name="descrizione_materiale" rows="4"></textarea>
                            </div>

                            <div class="form-group mb-0">
                                <label for="materialFile"><?= $translator->translate('teacher.materials.field.file') ?></label>
                                <input type="file" class="form-control-file" id="materialFile" name="material_file">
                                <small class="text-muted"><?= $translator->translate('teacher.materials.file.keep_current_hint') ?></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveMaterialButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
