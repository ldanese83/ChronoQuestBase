<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$students = $students ?? [];
$studentPowers = $studentPowers ?? [];
$selectedStudentUserId = (int) ($selectedStudentUserId ?? 0);
$translator = new TranslationService();
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.powers.assigned.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.powers.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.powers.assigned.filter.title') ?></h6>
            </div>
            <div class="card-body">
                <form method="get" action="/docenti/poteri/assegnati" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="studentFilter" class="form-label"><?= $translator->translate('teacher.powers.assigned.filter.student') ?></label>
                        <select class="form-control" id="studentFilter" name="studente">
                            <option value="0"><?= $translator->translate('teacher.powers.assigned.filter.all_students') ?></option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= (int) $student['id_utente'] ?>" <?= ((int) $student['id_utente'] === $selectedStudentUserId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(trim((string) ($student['cognome'] ?? '') . ' ' . (string) ($student['nome'] ?? ''))) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.powers.assigned.button.show') ?></button>
                        <a href="/docenti/poteri/assegnati" class="btn btn-outline-secondary"><?= $translator->translate('common.reset') ?></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" id="assignedPowerCards">
            <?php if ($studentPowers === []): ?>
                <div class="col-12">
                    <div class="alert alert-info"><?= $translator->translate('teacher.powers.assigned.none_for_filter') ?></div>
                </div>
            <?php endif; ?>

            <?php foreach ($studentPowers as $power): ?>
                <?php
                $translatedPowerName = trim((string) ($power['nome_potere_en'] ?? ''));
                $translatedPowerDescription = trim((string) ($power['descrizione_potere_en'] ?? ''));
                $displayPowerName = $useEnglishDbTranslations && $translatedPowerName !== ''
                    ? $translatedPowerName
                    : (string) ($power['nome_potere'] ?? '');
                $displayPowerDescription = $useEnglishDbTranslations && $translatedPowerDescription !== ''
                    ? $translatedPowerDescription
                    : (string) ($power['descrizione_potere'] ?? '');
                ?>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($displayPowerName) ?></h6>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <img src="<?= htmlspecialchars((string) ($power['img_potere'] ?? '')) ?>" alt="<?= htmlspecialchars($translator->translate('teacher.powers.alt.power')) ?>" style="max-width: 140px; max-height: 140px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                            </div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.powers.assigned.label.student') ?>:</strong> <?= htmlspecialchars(trim((string) ($power['cognome'] ?? '') . ' ' . (string) ($power['nome'] ?? ''))) ?></div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.powers.field.level') ?>:</strong> <?= (int) ($power['livello'] ?? 0) ?></div>
                            <div class="mb-2"><strong><?= $translator->translate('teacher.powers.field.mana') ?>:</strong> <?= (int) ($power['mana_necessario'] ?? 0) ?></div>
                            <div class="text-muted mt-auto"><?= htmlspecialchars_decode($displayPowerDescription, ENT_QUOTES) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
