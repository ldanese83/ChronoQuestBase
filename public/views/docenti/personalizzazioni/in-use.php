<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$students = $students ?? [];
$selectedStudentId = (int) ($selectedStudentId ?? 0);
$activeCustomizations = $activeCustomizations ?? [];
$ownedCustomizations = $ownedCustomizations ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.customizations.in_use.title') ?></h1>
            <form method="GET" action="/docenti/personalizzazioni/in-uso" class="d-flex align-items-center gap-2">
                <label for="studentFilter" class="mb-0"><?= $translator->translate('teacher.customizations.in_use.student_filter') ?></label>
                <select name="studente" id="studentFilter" class="form-control" onchange="this.form.submit()">
                    <?php foreach ($students as $student): ?>
                        <?php $id = (int) ($student['id_studente'] ?? 0); ?>
                        <option value="<?= $id ?>" <?= $id === $selectedStudentId ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) (($student['cognome'] ?? '') . ' ' . ($student['nome'] ?? ''))) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.in_use.active_section') ?></h6></div>
            <div class="card-body">
                <div class="row">
                    <?php if (empty($activeCustomizations)): ?>
                        <div class="col-12"><p class="text-muted mb-0"><?= $translator->translate('teacher.customizations.in_use.none_active') ?></p></div>
                    <?php endif; ?>
                    <?php foreach ($activeCustomizations as $item): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100 border-left-success">
                                <?php if (!empty($item['img'])): ?>
                                    <img src="<?= htmlspecialchars((string) $item['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($translator->translate('teacher.customizations.alt.customization')) ?>" style="height:160px;object-fit:contain;background:#f8f9fc;">
                                <?php endif; ?>
                                <div class="card-body p-3">
                                    <div class="small text-success text-uppercase"><?= $translator->translate('teacher.customizations.type.' . (string) ($item['tipo'] ?? '')) ?></div>
                                    <strong><?= html_entity_decode((string) ($item['nome_personalizzazione'] ?? '')) ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.in_use.owned_section') ?></h6></div>
            <div class="card-body">
                <div class="row">
                    <?php if (empty($ownedCustomizations)): ?>
                        <div class="col-12"><p class="text-muted mb-0"><?= $translator->translate('teacher.customizations.in_use.none_owned') ?></p></div>
                    <?php endif; ?>
                    <?php foreach ($ownedCustomizations as $item): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100 <?= ((int) ($item['in_uso'] ?? 0) === 1) ? 'border-left-success' : 'border-left-secondary' ?>">
                                <?php if (!empty($item['img'])): ?>
                                    <img src="<?= htmlspecialchars((string) $item['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($translator->translate('teacher.customizations.alt.customization')) ?>" style="height:160px;object-fit:contain;background:#f8f9fc;">
                                <?php endif; ?>
                                <div class="card-body p-3">
                                    <div class="small text-uppercase text-primary"><?= $translator->translate('teacher.customizations.type.' . (string) ($item['tipo'] ?? '')) ?></div>
                                    <strong><?= html_entity_decode((string) ($item['nome_personalizzazione'] ?? '')) ?></strong>
                                    <div class="small mt-1">
                                        <?= $translator->translate('teacher.customizations.in_use.status') ?>: <?= ((int) ($item['in_uso'] ?? 0) === 1) ? $translator->translate('teacher.customizations.in_use.status_active') : $translator->translate('teacher.customizations.in_use.status_inactive') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
