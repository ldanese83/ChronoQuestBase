<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$deliveries = $deliveries ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.quest.unevaluated.title') ?> <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong>
            </h1>
            <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.chapter_list.back_to_map') ?>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.quest.unevaluated.table_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="questUnevaluatedDeliveriesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.first_name') ?></th>
                                <th><?= $translator->translate('teacher.quest.deliveries.field.last_name') ?></th>
                                <th><?= $translator->translate('teacher.quest.unevaluated.field.chapter') ?></th>
                                <th><?= $translator->translate('teacher.quest.unevaluated.field.exercise') ?></th>
                                <th><?= $translator->translate('teacher.quest.unevaluated.field.problem') ?></th>
                                <th><?= $translator->translate('teacher.quest.student_delivery.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deliveries as $delivery): ?>
                                <?php
                                $hasProblem = (int) ($delivery['problema'] ?? 0) === 1;
                                $correctionUrl = '/docenti/quest/'
                                    . (int) ($quest['id_quest'] ?? 0)
                                    . '/capitolo/' . (int) ($delivery['id_capitolo'] ?? 0)
                                    . '/esercizi/' . (int) ($delivery['id_esercizio'] ?? 0)
                                    . '/consegne/' . (int) ($delivery['id_studente'] ?? 0);
                                $problemUrl = '/docenti/quest/'
                                    . (int) ($quest['id_quest'] ?? 0)
                                    . '/consegne/' . (int) ($delivery['id_consegna'] ?? 0)
                                    . '/problema';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($delivery['nome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($delivery['cognome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($delivery['nome_capitolo'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($delivery['nome_esercizio'] ?? '')) ?></td>
                                    <td>
                                        <?php if ($hasProblem): ?>
                                            <span class="badge bg-danger"><?= $translator->translate('teacher.quest.unevaluated.problem.yes') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?= $translator->translate('teacher.quest.unevaluated.problem.no') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= htmlspecialchars($correctionUrl) ?>" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                                            <i class="fas fa-pen fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.unevaluated.correct') ?>
                                        </a>
                                        <button
                                            type="button"
                                            class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm delivery-problem-btn"
                                            data-toggle="modal"
                                            data-target="#deliveryProblemModal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deliveryProblemModal"
                                            data-action="<?= htmlspecialchars($problemUrl) ?>"
                                            data-problem="<?= $hasProblem ? '1' : '0' ?>"
                                            data-description="<?= htmlspecialchars((string) ($delivery['descrizione_problema'] ?? ''), ENT_QUOTES) ?>"
                                        >
                                            <i class="fas fa-exclamation-triangle fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.unevaluated.problem.button') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deliveryProblemModal" tabindex="-1" role="dialog" aria-labelledby="deliveryProblemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="deliveryProblemForm" method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deliveryProblemModalLabel"><?= $translator->translate('teacher.quest.unevaluated.problem.modal_title') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="deliveryProblemCheckbox" name="problema">
                                <label class="form-check-label" for="deliveryProblemCheckbox">
                                    <?= $translator->translate('teacher.quest.unevaluated.problem.checkbox') ?>
                                </label>
                            </div>
                            <div class="form-group mb-0">
                                <label for="deliveryProblemDescription"><?= $translator->translate('teacher.quest.unevaluated.problem.description') ?></label>
                                <textarea class="form-control" id="deliveryProblemDescription" name="descrizione_problema" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.quest.unevaluated.problem.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
