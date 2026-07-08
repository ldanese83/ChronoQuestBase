<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$classroom = $classroom ?? null;
$team = $team ?? null;
$isCreator = (bool) ($isCreator ?? false);
$teamMembers = $teamMembers ?? [];
$pendingInvites = $pendingInvites ?? [];
$rejectedInvites = $rejectedInvites ?? [];
$teamPendingInvitees = $teamPendingInvitees ?? [];
$availableInvitees = $availableInvitees ?? [];
$defaultEmblems = $defaultEmblems ?? [];
$teamTypes = $teamTypes ?? [];
?>

<?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
<div class="container-fluid squadra-page">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) ($classroom['colore'] ?? '#1f2937')) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) ($classroom['icona'] ?? 'fa-school')) ?>"></i>
                </div>
                <h1 class="class-title"><?= $translator->translate('student.teams.class') ?> <?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?></h1>
            </div>
        </div>
    <?php endif; ?>

    <div class="team-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <h1 class="team-title">&#9876; <?= $translator->translate('student.teams.title') ?></h1>
        </div>
    </div>

    <?php if (is_array($team)): ?>
        <div class="card shadow mb-4 squadra-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.teams.data') ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php if ((string) ($team['emblema_squadra'] ?? '') !== ''): ?>
                            <div class="team-emblem-frame">
                                <img src="<?= htmlspecialchars((string) $team['emblema_squadra']) ?>" alt="<?= htmlspecialchars($translator->translate('student.teams.alt.emblem')) ?>">
                            </div>
                        <?php else: ?>
                            <div class="text-muted"><?= $translator->translate('student.teams.no_emblem') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <p><strong><?= $translator->translate('student.teams.name') ?>:</strong> <?= htmlspecialchars((string) ($team['nome_squadra'] ?? '')) ?></p>
                        <p><strong><?= $translator->translate('student.teams.type') ?>:</strong> <?= htmlspecialchars((string) ($team['tipologia_label'] ?? $team['tipologia'] ?? '-')) ?></p>
                        <p><strong><?= $translator->translate('student.teams.status') ?>:</strong>
                            <?php if ((int) ($team['approvata'] ?? 0) === 1): ?>
                                <span class="badge badge-success"><?= $translator->translate('student.teams.approved') ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= $translator->translate('student.teams.waiting_approval') ?></span>
                            <?php endif; ?>
                        </p>
                        <p><strong><?= $translator->translate('student.teams.members') ?>:</strong>
                            <?php if ($teamMembers === []): ?>
                                <?= $translator->translate('student.teams.no_members') ?>
                            <?php else: ?>
                                <?= htmlspecialchars(implode(', ', array_map(static fn (array $member): string => trim((string) ($member['cognome'] ?? '') . ' ' . (string) ($member['nome'] ?? '')), $teamMembers))) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$isCreator): ?>
            <div class="card shadow mb-4 squadra-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.teams.manage') ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="/studenti/squadra/lascia" onsubmit="return confirm(window.cqT('student.teams.confirm.leave', <?= htmlspecialchars(json_encode($translator->translate('student.teams.confirm.leave'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>));">
                        <button type="submit" class="btn btn-danger-team btn-team"><?= $translator->translate('student.teams.leave') ?></button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($isCreator): ?>
            <div class="card shadow mb-4 squadra-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.teams.edit') ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="/studenti/squadra/save">
                        <input type="hidden" name="id_squadra" value="<?= (int) ($team['id_squadra'] ?? 0) ?>">
                        <div class="form-group">
                            <label for="nome_squadra"><?= $translator->translate('student.teams.name') ?></label>
                            <input class="form-control" type="text" id="nome_squadra" name="nome_squadra" value="<?= htmlspecialchars((string) ($team['nome_squadra'] ?? ''), ENT_QUOTES) ?>" required>
                        </div>

                        <div class="form-group">
                            <label><?= $translator->translate('student.teams.type') ?></label>
                            <input type="hidden" name="tipologia" value="<?= htmlspecialchars((string) ($team['tipologia'] ?? 'mercanti')) ?>">
                            <div class="team-type-grid">
                                <?php foreach ($teamTypes as $key => $info): ?>
                                    <label class="team-type-option is-locked">
                                        <input type="radio" value="<?= htmlspecialchars((string) $key) ?>" <?= (string) ($team['tipologia'] ?? '') === (string) $key ? 'checked' : '' ?> disabled>
                                        <span class="team-type-card" data-toggle="tooltip" data-placement="top" title="<?= htmlspecialchars((string) ($info['tooltip'] ?? '')) ?>">
                                            <span class="team-type-image-wrapper">
                                                <img src="/assets/images/Squadre/Tipologie/<?= htmlspecialchars((string) $key) ?>.png" alt="<?= htmlspecialchars((string) ($info['label'] ?? $key)) ?>">
                                            </span>
                                            <span class="team-type-label"><?= htmlspecialchars((string) ($info['label'] ?? $key)) ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if ($teamPendingInvitees !== []): ?>
                            <p><strong><?= $translator->translate('student.teams.pending_invites') ?>:</strong>
                                <?= htmlspecialchars(implode(', ', array_map(static fn (array $invitee): string => trim((string) ($invitee['cognome'] ?? '') . ' ' . (string) ($invitee['nome'] ?? '')), $teamPendingInvitees))) ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($rejectedInvites !== []): ?>
                            <p><strong><?= $translator->translate('student.teams.rejected_invites') ?>:</strong>
                                <?= htmlspecialchars(implode(', ', array_map(static fn (array $invitee): string => trim((string) ($invitee['cognome'] ?? '') . ' ' . (string) ($invitee['nome'] ?? '')), $rejectedInvites))) ?>
                            </p>
                        <?php endif; ?>

                        <div class="form-group">
                            <label><?= $translator->translate('student.teams.invite_classmates') ?></label>
                            <div class="row">
                                <?php if ($availableInvitees === []): ?>
                                    <div class="col-md-12 text-muted"><?= $translator->translate('student.teams.no_invitees') ?></div>
                                <?php else: ?>
                                    <?php foreach ($availableInvitees as $invitee): ?>
                                        <div class="col-md-6">
                                            <input type="checkbox" name="studenti_invito[]" value="<?= (int) ($invitee['id_studente'] ?? 0) ?>">
                                            <?= htmlspecialchars((string) ($invitee['nomecognome'] ?? '')) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-team btn-team"><?= $translator->translate('student.teams.save_changes') ?></button>
                    </form>

                    <form method="post" action="/studenti/squadra/<?= (int) ($team['id_squadra'] ?? 0) ?>/delete" onsubmit="return confirm(window.cqT('student.teams.confirm.delete', <?= htmlspecialchars(json_encode($translator->translate('student.teams.confirm.delete'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>));">
                        <button type="submit" class="btn btn-danger-team btn-team"><?= $translator->translate('student.teams.delete') ?></button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <?php if ($pendingInvites !== []): ?>
            <div class="card shadow mb-4 squadra-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.teams.received_invites') ?></h6>
                </div>
                <div class="card-body">
                    <?php foreach ($pendingInvites as $invite): ?>
                        <div class="border rounded p-3 mb-3">
                            <p><strong><?= $translator->translate('student.teams.team') ?>:</strong> <?= htmlspecialchars((string) ($invite['nome_squadra'] ?? '')) ?></p>
                            <p><strong><?= $translator->translate('student.teams.type') ?>:</strong> <?= htmlspecialchars((string) ($invite['tipologia_label'] ?? $invite['tipologia'] ?? '')) ?></p>
                            <p><strong><?= $translator->translate('student.teams.creator') ?>:</strong> <?= htmlspecialchars(trim((string) ($invite['cognome'] ?? '') . ' ' . (string) ($invite['nome'] ?? ''))) ?></p>
                            <form method="post" action="/studenti/squadra/invito" class="d-inline">
                                <input type="hidden" name="id_invito" value="<?= (int) ($invite['id_invito'] ?? 0) ?>">
                                <input type="hidden" name="azione" value="accetta">
                                <button type="submit" class="btn btn-success-team btn-team btn-sm"><?= $translator->translate('student.teams.accept') ?></button>
                            </form>
                            <form method="post" action="/studenti/squadra/invito" class="d-inline">
                                <input type="hidden" name="id_invito" value="<?= (int) ($invite['id_invito'] ?? 0) ?>">
                                <input type="hidden" name="azione" value="rifiuta">
                                <button type="submit" class="btn btn-danger-team btn-team btn-sm"><?= $translator->translate('student.teams.reject') ?></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4 squadra-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('student.teams.create_new') ?></h6>
            </div>
            <div class="card-body">
                <form method="post" action="/studenti/squadra/save" enctype="multipart/form-data">
                    <input type="hidden" name="id_squadra" value="0">
                    <div class="form-group">
                        <label for="nome_squadra_new"><?= $translator->translate('student.teams.name') ?></label>
                        <input class="form-control" type="text" id="nome_squadra_new" name="nome_squadra" required>
                    </div>

                    <div class="form-group">
                        <label><?= $translator->translate('student.teams.type') ?></label>
                        <div class="team-type-grid">
                            <?php foreach ($teamTypes as $key => $info): ?>
                                <label class="team-type-option">
                                    <input type="radio" name="tipologia" value="<?= htmlspecialchars((string) $key) ?>" <?= $key === 'mercanti' ? 'checked' : '' ?>>
                                    <span class="team-type-card" data-toggle="tooltip" data-placement="top" title="<?= htmlspecialchars((string) ($info['tooltip'] ?? '')) ?>">
                                        <span class="team-type-image-wrapper">
                                            <img src="/assets/images/Squadre/Tipologie/<?= htmlspecialchars((string) $key) ?>.png" alt="<?= htmlspecialchars((string) ($info['label'] ?? $key)) ?>">
                                        </span>
                                        <span class="team-type-label"><?= htmlspecialchars((string) ($info['label'] ?? $key)) ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= $translator->translate('student.teams.emblem') ?></label>
                        <div>
                            <label><input type="radio" name="emblema_tipo" value="upload" checked> <?= $translator->translate('student.teams.emblem.upload') ?></label>
                        </div>
                        <input type="file" name="emblema_squadra" accept=".png,.jpg,.jpeg,.gif,.webp">
                        <div style="margin-top:10px;">
                            <label><input type="radio" name="emblema_tipo" value="default"> <?= $translator->translate('student.teams.emblem.default') ?></label>
                        </div>
                        <div style="margin-top:5px;">
                            <?php if ($defaultEmblems === []): ?>
                                <div class="text-muted"><small><?= $translator->translate('student.teams.no_default_emblems') ?></small></div>
                            <?php else: ?>
                                <?php foreach ($defaultEmblems as $default): ?>
                                    <label style="display:inline-block;margin-right:10px;margin-bottom:10px;">
                                        <input type="radio" name="emblema_default" value="<?= htmlspecialchars((string) ($default['name'] ?? '')) ?>" style="margin-right:5px;">
                                        <img src="<?= htmlspecialchars((string) ($default['path'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($default['name'] ?? '')) ?>" style="max-width:80px;max-height:80px;border:1px solid #ccc;">
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= $translator->translate('student.teams.invite_classmates') ?></label>
                        <div class="row">
                            <?php if ($availableInvitees === []): ?>
                                <div class="col-md-12 text-muted"><?= $translator->translate('student.teams.no_invitees') ?></div>
                            <?php else: ?>
                                <?php foreach ($availableInvitees as $invitee): ?>
                                    <div class="col-md-6">
                                        <input type="checkbox" name="studenti_invito[]" value="<?= (int) ($invitee['id_studente'] ?? 0) ?>">
                                        <?= htmlspecialchars((string) ($invitee['nomecognome'] ?? '')) ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success-team btn-team"><?= $translator->translate('student.teams.create') ?></button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
