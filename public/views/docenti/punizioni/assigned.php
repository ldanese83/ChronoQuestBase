<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$assignedPunishments = $assignedPunishments ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.punishments.assigned.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.punishments.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.punishments.assigned.section.students') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="assignedPunishmentTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width:14%"><?= $translator->translate('teacher.punishments.assigned.field.first_name') ?></th>
                            <th style="width:14%"><?= $translator->translate('teacher.punishments.assigned.field.last_name') ?></th>
                            <th style="width:36%"><?= $translator->translate('teacher.punishments.field.description') ?></th>
                            <th style="width:12%"><?= $translator->translate('teacher.punishments.assigned.field.due_date') ?></th>
                            <th style="width:12%"><?= $translator->translate('teacher.punishments.field.days') ?></th>
                            <th style="width:12%"><?= $translator->translate('teacher.punishments.assigned.field.delivery') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($assignedPunishments as $assigned): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($assigned['nome'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($assigned['cognome'] ?? '')) ?></td>
                                <td><?= htmlspecialchars_decode((string) ($assigned['descrizione_punizione'] ?? ''), ENT_QUOTES) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($assigned['data_scadenza'])): ?>
                                        <?= htmlspecialchars((string) date('d/m/Y', strtotime((string) $assigned['data_scadenza']))) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= (int) ($assigned['giorni_per_consegna'] ?? 0) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($assigned['link_consegna'])): ?>
                                        <a href="<?= htmlspecialchars((string) $assigned['link_consegna']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> <?= $translator->translate('teacher.punishments.assigned.button.download') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><?= $translator->translate('teacher.punishments.assigned.no_file') ?></span>
                                    <?php endif; ?>
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
