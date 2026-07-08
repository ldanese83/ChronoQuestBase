<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$students = $students ?? [];
$studentBadges = $studentBadges ?? [];
$selectedStudentUserId = (int) ($selectedStudentUserId ?? 0);
$translator = new TranslationService();
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.badges.assigned.title') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.classes.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.badges.student_filter') ?></h6>
            </div>
            <div class="card-body">
                <form method="get" action="/docenti/badge/assegnati" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="studentFilter" class="form-label"><?= $translator->translate('teacher.badges.student.select') ?></label>
                        <select class="form-control" id="studentFilter" name="studente">
                            <option value="0"><?= $translator->translate('teacher.badges.student.all') ?></option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= (int) $student['id_utente'] ?>" <?= ((int) $student['id_utente'] === $selectedStudentUserId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(trim((string) ($student['cognome'] ?? '') . ' ' . (string) ($student['nome'] ?? ''))) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.badges.assigned.view') ?></button>
                        <a href="/docenti/badge/assegnati" class="btn btn-outline-secondary"><?= $translator->translate('teacher.badges.filters.reset') ?></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" id="assignedBadgeCards">
            <?php if ($studentBadges === []): ?>
                <div class="col-12">
                    <div class="alert alert-info"><?= $translator->translate('teacher.badges.assigned.none') ?></div>
                </div>
            <?php endif; ?>

            <?php foreach ($studentBadges as $badge): ?>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars((string) ($badge['nome_badge'] ?? '')) ?></h6>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <img src="<?= htmlspecialchars((string) ($badge['img_badge'] ?? '')) ?>" alt="Badge" style="max-width: 140px; max-height: 140px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                            </div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.badges.student.label') ?>:</strong> <?= htmlspecialchars(trim((string) ($badge['cognome'] ?? '') . ' ' . (string) ($badge['nome'] ?? ''))) ?></div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.badges.subject') ?>:</strong> <?= htmlspecialchars((string) ($badge['nome_materia'] ?? '-')) ?></div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.badges.topic') ?>:</strong> <?= htmlspecialchars((string) ($badge['nome_argomento'] ?? '-')) ?></div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.badges.assigned_date') ?>:</strong> <?= !empty($badge['data_conquista']) ? htmlspecialchars(date('d/m/Y H:i', strtotime((string) $badge['data_conquista']))) : '-' ?></div>
                            <div class="text-muted mt-auto"><?= htmlspecialchars_decode((string) ($badge['descrizione'] ?? ''), ENT_QUOTES) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
