<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$templates = is_array($templates ?? null) ? $templates : [];
$selectedTemplate = is_string($selectedTemplate ?? null) ? trim((string) $selectedTemplate) : null;
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
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.print_templates.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.print_templates.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?></h5>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadTemplateModal">
                <?= $translator->translate('testcreator.print_templates.add_template') ?>
            </button>
        </div>

        <div class="card shadow mb-4 border-left-danger">
            <div class="card-body">
                <div class="alert alert-danger mb-0">
                    <h6 class="mb-0"><?= $translator->translate('testcreator.print_templates.zip_help') ?></h6>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.print_templates.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= $translator->translate('testcreator.print_templates.template_name') ?></th>
                                <th style="width: 220px;"><?= $translator->translate('testcreator.print_templates.action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($templates === []): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted"><?= $translator->translate('testcreator.print_templates.empty') ?></td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($templates as $template): ?>
                                <?php
                                $templateName = (string) $template;
                                $isSelected = $templateName === $selectedTemplate || ($templateName === 'DalCero' && ($selectedTemplate === null || $selectedTemplate === ''));
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($templateName) ?></td>
                                    <td>
                                        <?php if ($isSelected): ?>
                                            <div class="alert alert-warning text-center py-2 mb-0"><?= $translator->translate('testcreator.print_templates.selected') ?></div>
                                        <?php else: ?>
                                            <form method="POST" action="/testcreator/template-stampa/seleziona" class="d-inline">
                                                <input type="hidden" name="template_name" value="<?= htmlspecialchars($templateName, ENT_QUOTES) ?>">
                                                <button type="submit" class="btn btn-primary btn-sm w-100"><?= $translator->translate('testcreator.print_templates.select') ?></button>
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

        <div class="modal fade" id="uploadTemplateModal" tabindex="-1" aria-labelledby="uploadTemplateLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="/testcreator/template-stampa/upload" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadTemplateLabel"><?= $translator->translate('testcreator.print_templates.modal.title') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" name="file" accept=".zip" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.subjects.modal.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
