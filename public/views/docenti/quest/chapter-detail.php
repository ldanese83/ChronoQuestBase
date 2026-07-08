<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exercises = $exercises ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                Quest <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong>
                - <?= $translator->translate('teacher.quest.chapter_detail.chapter_label') ?>: <strong><?= htmlspecialchars((string) ($chapter['nome_capitolo'] ?? '')) ?></strong>
            </h1>
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.chapter_list.back_to_map') ?>
                </a>
                <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.exercise.add') ?>
                </a>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.quest.chapter_detail.exercises') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="chapterExercisesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:5%"><?= $translator->translate('teacher.quest.chapter_list.field.progressive') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.quest.chapter_detail.field.exercise_name') ?></th>
                                <th style="width:15%"><?= $translator->translate('teacher.quest.chapter_detail.field.topic') ?></th>
                                <th style="width:15%"><?= $translator->translate('teacher.quest.chapter_detail.field.type') ?></th>
                                <th style="width:10%"><?= $translator->translate('teacher.quest.chapter_detail.field.experience_points') ?></th>
                                <th style="width:10%"><?= $translator->translate('teacher.quest.chapter_detail.field.activate') ?></th>
                                <th style="width:12%"><?= $translator->translate('common.edit') ?></th>
                                <th style="width:12%"><?= $translator->translate('common.delete') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $exercise): ?>
                                <?php
                                $isActive = (int) ($exercise['attivo'] ?? 0) === 1;
                                $pending = (int) ($exercise['tot_mancanti'] ?? 0);
                                $rowStyle = '';
                                if ($isActive && $pending === 0) {
                                    $rowStyle = 'background-color:#dbfeff';
                                } elseif ($isActive && $pending > 0) {
                                    $rowStyle = 'background-color:#ffe54f';
                                }
                                $translatedExerciseType = trim((string) ($exercise['tipo_en'] ?? ''));
                                $displayExerciseType = $useEnglishDbTranslations && $translatedExerciseType !== ''
                                    ? $translatedExerciseType
                                    : (string) ($exercise['tipo'] ?? '');
                                ?>
                                <tr<?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>><?= (int) ($exercise['progressivo'] ?? 0) ?></td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>><?= htmlspecialchars((string) ($exercise['nome_argomento'] ?? '')) ?></td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>><?= htmlspecialchars($displayExerciseType) ?></td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?>><?= (int) ($exercise['punti_esperienza'] ?? 0) ?></td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?> class="text-center">
                                        <?php if (!$isActive): ?>
                                            <form method="POST" action="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/activate" onsubmit="return confirm(<?= htmlspecialchars(json_encode($translator->translate('teacher.quest.chapter_detail.activate_confirm'), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>);">
                                                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                                                    <i class="fas fa-exclamation fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.chapter_detail.activate') ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/consegne" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                                <i class="fas fa-paperclip fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.chapter_detail.view_deliveries') ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?> class="text-center">
                                        <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/<?= $isActive ? 'visualizza' : 'modifica' ?>" class="d-none d-sm-inline-block btn btn-sm <?= $isActive ? 'btn-info' : 'btn-warning' ?> shadow-sm">
                                            <i class="fas <?= $isActive ? 'fa-eye' : 'fa-pen' ?> fa-sm text-white-50 me-1"></i><?= $isActive ? $translator->translate('teacher.quest.chapter_detail.view') : $translator->translate('common.edit') ?>
                                        </a>
                                    </td>
                                    <td <?= $rowStyle !== '' ? ' style="' . $rowStyle . '"' : '' ?> class="text-center">
                                        <?php if (!$isActive): ?>
                                            <form method="POST" action="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitolo/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>/esercizi/<?= (int) ($exercise['id_esercizio'] ?? 0) ?>/delete" onsubmit="return confirm(<?= htmlspecialchars(json_encode(sprintf($translator->translate('teacher.quest.chapter_detail.delete_confirm'), (string) ($exercise['nome_capitolo'] ?? '')), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>);">
                                                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                                                    <i class="fas fa-trash fa-sm text-white-50 me-1"></i><?= $translator->translate('common.delete') ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm disabled">
                                                <i class="fas fa-trash fa-sm text-white-50 me-1"></i><?= $translator->translate('common.delete') ?>
                                            </button>
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
