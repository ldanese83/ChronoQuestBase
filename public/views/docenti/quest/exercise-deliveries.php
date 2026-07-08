<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exercise = $exercise ?? null;
$students = $students ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null && $exercise !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.quest.delivery.exercise.title') ?> <strong><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></strong>
            </h1>
            <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitoli/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.add_exercise.back_to_chapter') ?>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.quest.deliveries.class_students') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="exerciseDeliveriesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.first_name') ?></th>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.last_name') ?></th>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.evaluation') ?></th>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.delivery') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <?php
                                $isEvaluated = (int) ($student['valutato'] ?? 0) === 1;
                                $deliveryId = (int) ($student['id_consegna'] ?? 0);
                                $grade = (int) ($student['valutazione'] ?? 0);
                                ?>
                                <tr<?= $isEvaluated ? ' style="background-color:#dbfeff"' : '' ?>>
                                    <td><?= htmlspecialchars((string) ($student['nome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($student['cognome'] ?? '')) ?></td>
                                    <td>
                                        <?php if ($grade <= 0): ?>
                                            <?= $translator->translate('teacher.quest.deliveries.not_evaluated') ?>
                                        <?php elseif ($grade > 7): ?>
                                            <span style="color:green;font-weight:bold"><?= $grade ?></span>
                                        <?php elseif ($grade > 4): ?>
                                            <span style="color:orange;font-weight:bold"><?= $grade ?></span>
                                        <?php else: ?>
                                            <span style="color:red;font-weight:bold"><?= $grade ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($deliveryId <= 0): ?>
                                            <?= $translator->translate('teacher.quest.deliveries.not_delivered') ?>
                                        <?php else: ?>
                                            <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/consegne/<?= (int) ($student['id_studente'] ?? 0) ?>" class="d-none d-sm-inline-block btn btn-sm <?= $isEvaluated ? 'btn-danger' : 'btn-warning' ?> shadow-sm">
                                                <i class="fas fa-pen fa-sm text-white-50 me-1"></i><?= $isEvaluated ? $translator->translate('teacher.quest.deliveries.already_evaluated') : $translator->translate('teacher.quest.deliveries.view_delivery') ?>
                                            </a>
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
