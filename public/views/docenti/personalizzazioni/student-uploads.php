<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$pendingUploads = $pendingUploads ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.customizations.student_uploads.page_title') ?></h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.student_uploads.pending_section') ?></h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="studentUploadsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr><th><?= $translator->translate('teacher.customizations.student_uploads.field.student') ?></th><th><?= $translator->translate('teacher.customizations.student_uploads.field.personal_name') ?></th><th><?= $translator->translate('teacher.customizations.field.image') ?></th><th><?= $translator->translate('teacher.customizations.student_uploads.approve') ?></th><th><?= $translator->translate('teacher.customizations.student_uploads.reject') ?></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pendingUploads as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) (($item['nome'] ?? '') . ' ' . ($item['cognome'] ?? ''))) ?></td>
                                <td><?= html_entity_decode((string) ($item['nome_personalizzazione'] ?? '')) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($item['img'])): ?>
                                        <img src="<?= htmlspecialchars((string) $item['img']) ?>" style="max-width:140px;max-height:110px" class="img-thumbnail" alt="<?= htmlspecialchars($translator->translate('teacher.customizations.student_uploads.alt.upload')) ?>">
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="/docenti/personalizzazioni/studenti/<?= (int) ($item['id_personalizzazione'] ?? 0) ?>/approve">
                                        <button class="btn btn-sm btn-success" type="submit"><?= $translator->translate('teacher.customizations.student_uploads.approve') ?></button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="/docenti/personalizzazioni/studenti/<?= (int) ($item['id_personalizzazione'] ?? 0) ?>/reject">
                                        <button class="btn btn-sm btn-danger" type="submit"><?= $translator->translate('teacher.customizations.student_uploads.reject') ?></button>
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
