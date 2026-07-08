<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$teams = $teams ?? [];
$classStudents = $classStudents ?? [];
$defaultEmblems = $defaultEmblems ?? [];
$teamTypes = $teamTypes ?? [];
$teamTypeLabels = $teamTypeLabels ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teams.teams') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.classes.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateTeamModal">
                <i class="fas fa-users fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('teams.add') ?>
            </button>
        </div>

        <div id="team-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teams.inserted') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="teamTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:14%"><?= $translator->translate('teams.name') ?></th>
                                <th style="width:10%"><?= $translator->translate('teams.type') ?></th>
                                <th style="width:15%"><?= $translator->translate('teams.creator') ?></th>
                                <th style="width:13%"><?= $translator->translate('teams.emblem') ?></th>
                                <th style="width:25%"><?= $translator->translate('teams.students') ?></th>
                                <th style="width:8%"><?= $translator->translate('teams.status') ?></th>
                                <th style="width:15%"><?= $translator->translate('teams.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teams as $team): ?>
                                <?php
                                $isApproved = (int) ($team['approvata'] ?? 0) === 1;
                                $memberText = trim((string) ($team['membri'] ?? ''));
                                $invitees = $team['invitati'] ?? [];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($team['nome_squadra'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teamTypeLabels[$team['tipologia'] ?? ''] ?? '-')) ?></td>
                                    <td>
                                        <?php if (!empty($team['creatore_cognome']) || !empty($team['creatore_nome'])): ?>
                                            <?= htmlspecialchars(trim((string) ($team['creatore_cognome'] ?? '') . ' ' . (string) ($team['creatore_nome'] ?? ''))) ?>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('teams.teacher') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($team['emblema_squadra'])): ?>
                                            <img src="<?= htmlspecialchars((string) $team['emblema_squadra']) ?>" alt="Emblema" style="max-width: 120px; max-height: 120px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('teams.noemblem') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><strong><?= $translator->translate('teams.members_label') ?>:</strong> <?= htmlspecialchars($memberText !== '' ? $memberText : '-') ?></div>
                                        <?php if (!$isApproved): ?>
                                            <div class="mt-1"><strong><?= $translator->translate('teams.invitees_label') ?>:</strong> <?= htmlspecialchars($invitees !== [] ? implode(', ', $invitees) : $translator->translate('teams.no_invites')) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $isApproved ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= $isApproved ? $translator->translate('teams.approved') : $translator->translate('teams.waiting') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($isApproved): ?>
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-warning js-edit-team"
                                                    data-team-id="<?= (int) $team['id_squadra'] ?>"
                                                    data-team-name="<?= htmlspecialchars((string) ($team['nome_squadra'] ?? ''), ENT_QUOTES) ?>"
                                                    data-team-type="<?= htmlspecialchars((string) ($team['tipologia'] ?? ''), ENT_QUOTES) ?>"
                                                    data-team-member-ids="<?= htmlspecialchars((string) ($team['membro_ids'] ?? ''), ENT_QUOTES) ?>">
                                                    <?= $translator->translate('common.update') ?>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger js-delete-team" data-team-id="<?= (int) $team['id_squadra'] ?>" data-team-name="<?= htmlspecialchars((string) ($team['nome_squadra'] ?? ''), ENT_QUOTES) ?>">
                                                    <?= $translator->translate('common.delete') ?>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-success js-approve-team" data-team-id="<?= (int) $team['id_squadra'] ?>"><?= $translator->translate('teams.approve') ?></button>
                                                <button type="button" class="btn btn-sm btn-danger js-reject-team" data-team-id="<?= (int) $team['id_squadra'] ?>"><?= $translator->translate('teams.reject') ?></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="teamFormModal" tabindex="-1" role="dialog" aria-labelledby="teamFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="teamForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="teamFormModalLabel"><?= $translator->translate('teams.add') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= $translator->translate('common.close') ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="teamId" name="id_squadra" value="0">

                            <div class="form-group">
                                <label for="teamName"><?= $translator->translate('teams.name') ?></label>
                                <input type="text" class="form-control" id="teamName" name="nome_squadra" required>
                            </div>
                            <div class="form-group">
                                <label for="teamType"><?= $translator->translate('teams.type') ?></label>
                                <select class="form-control" id="teamType" name="tipologia" required>
                                    <option value=""><?= $translator->translate('teams.type.select') ?></option>
                                    <?php foreach ($teamTypes as $teamType): ?>
                                        <option value="<?= htmlspecialchars((string) $teamType) ?>"><?= htmlspecialchars((string) ($teamTypeLabels[$teamType] ?? $teamType)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="mb-2 d-block"><?= $translator->translate('teams.emblem') ?></label>
                                <div class="mb-2">
                                    <label class="mr-3"><input type="radio" name="emblema_tipo" value="keep"> <?= $translator->translate('teams.emblem.keep') ?></label>
                                    <label class="mr-3"><input type="radio" name="emblema_tipo" value="upload" checked> <?= $translator->translate('teams.emblem.upload') ?></label>
                                    <label><input type="radio" name="emblema_tipo" value="default"> <?= $translator->translate('teams.emblem.default') ?></label>
                                </div>

                                <input type="file" class="form-control-file" id="teamEmblemUpload" name="team_emblem" accept=".png,.jpg,.jpeg,.gif,.webp">

                                <div id="teamDefaultEmblems" class="mt-3 d-none">
                                    <?php if ($defaultEmblems !== []): ?>
                                        <?php foreach ($defaultEmblems as $defaultEmblem): ?>
                                            <label class="mr-2 mb-2 text-center" style="display:inline-block;">
                                                <input type="radio" name="default_emblema" value="<?= htmlspecialchars((string) $defaultEmblem['name']) ?>">
                                                <img src="<?= htmlspecialchars((string) $defaultEmblem['path']) ?>" alt="<?= htmlspecialchars((string) $defaultEmblem['name']) ?>" style="display:block;max-width:80px;max-height:80px;border:1px solid #ddd;margin-top:4px;">
                                            </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted mb-0"><?= $translator->translate('teams.no_default_emblems') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="d-block"><?= $translator->translate('teams.students_limit') ?></label>
                                <div class="border rounded p-2" style="max-height:230px; overflow:auto;">
                                    <?php foreach ($classStudents as $student): ?>
                                        <div class="form-check js-team-student-row" data-current-team-id="<?= (int) ($student['squadra_corrente'] ?? 0) ?>">
                                            <input class="form-check-input js-team-student" type="checkbox" name="team_students[]" value="<?= (int) ($student['id_studente'] ?? 0) ?>" id="team_student_<?= (int) ($student['id_studente'] ?? 0) ?>" data-current-team-id="<?= (int) ($student['squadra_corrente'] ?? 0) ?>">
                                            <label class="form-check-label" for="team_student_<?= (int) ($student['id_studente'] ?? 0) ?>">
                                                <?= htmlspecialchars((string) ($student['nomecognome'] ?? '')) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveTeamButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
