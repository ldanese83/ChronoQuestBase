<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$quest = $quest ?? null;
$problemDeliveries = $problemDeliveries ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
        <div class="quest-map-header mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="quest-map-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="quest-map-title"><?= $translator->translate('student.quest.problems.title') ?>: <?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></h1>
            </div>

            <a href="/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="quest-back-btn">
                <i class="fas fa-arrow-left"></i>
                <?= $translator->translate('student.quest.problems.back_to_map') ?>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.quest.problems.table_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="studentQuestProblemDeliveriesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= $translator->translate('student.quest.problems.field.chapter') ?></th>
                                <th><?= $translator->translate('student.quest.problems.field.exercise') ?></th>
                                <th><?= $translator->translate('student.quest.problems.field.description') ?></th>
                                <th><?= $translator->translate('student.quest.problems.field.delivery') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($problemDeliveries as $delivery): ?>
                                <?php
                                $exerciseUrl = '/studenti/quest/'
                                    . (int) ($quest['id_quest'] ?? 0)
                                    . '/capitoli/' . (int) ($delivery['id_capitolo'] ?? 0)
                                    . '/esercizi/' . (int) ($delivery['id_esercizio'] ?? 0);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($delivery['nome_capitolo'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($delivery['nome_esercizio'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($delivery['descrizione_problema'] ?? '')) ?></td>
                                    <td class="text-center">
                                        <a href="<?= htmlspecialchars($exerciseUrl) ?>" class="btn btn-sm btn-warning shadow-sm">
                                            <i class="fas fa-arrow-right fa-sm text-white-50 me-1"></i><?= $translator->translate('student.quest.problems.open_delivery') ?>
                                        </a>
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
