<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$classroom = $classroom ?? null;
$alerts = $alerts ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('alerts.page.title') ?>:
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('alerts.page.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
        </div>

        <div id="alerts-page-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('alerts.page.all') ?></h6>
                <small class="text-muted"></small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="alertsTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:65%">Alert</th>
                                <th style="width:15%"><?= $translator->translate('alerts.page.date') ?></th>
                                <th style="width:10%"><?= $translator->translate('alerts.page.status') ?></th>
                                <th style="width:10%"><?= $translator->translate('alerts.page.delete') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alerts as $alert): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div class="icon-circle <?= htmlspecialchars($alert['iconBgClass']) ?>">
                                                    <i class="fas <?= htmlspecialchars($alert['iconClass']) ?> text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($alert['text']) ?></div>
                                                <small class="text-muted"><?= $translator->translate('alerts.page.type') ?>: <?= htmlspecialchars($alert['type']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($alert['dateLabel']) ?></td>
                                    <td>
                                        <span class="badge <?= $alert['isRead'] ? 'bg-secondary' : 'bg-success' ?>">
                                            <?= $alert['isRead'] ? $translator->translate('alerts.page.read') : $translator->translate('alerts.page.unread') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger js-delete-alert" data-alert-id="<?= (int) $alert['id'] ?>">
                                            <i class="fas fa-trash me-1"></i>
                                            <?= $translator->translate('alerts.page.delete') ?>
                                        </button>
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
