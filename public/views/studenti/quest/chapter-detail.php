<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exercises = $exercises ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null): ?>
        <div class="quest-chapter-header mb-4">
            <div class="quest-chapter-left d-flex align-items-center gap-3">
                <img class="quest-chapter-img" src="<?= htmlspecialchars((string) ($quest['image_quest'] ?? '')) ?>" alt="<?= htmlspecialchars($translator->translate('student.quest.alt.quest_image')) ?>" />

                <div>
                    <h1 class="quest-title"><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></h1>
                    <div class="quest-chapter-sub"><?= $translator->translate('student.quest.chapter.label') ?> <span><?= htmlspecialchars((string) ($chapter['nome_capitolo'] ?? '')) ?></span></div>
                </div>
            </div>

            <a href="/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="quest-chapter-back">
                <i class="fas fa-arrow-left"></i>
                <?= $translator->translate('student.quest.chapter.back_to_map') ?>
            </a>
        </div>

        <div class="quest-table-card shadow mb-4">
            <div class="quest-table-header">
                <i class="fas fa-scroll"></i>
                <?= $translator->translate('student.quest.chapter.exercises') ?>
            </div>

            <div class="quest-table-body">
                <div class="table-responsive">
                    <table class="table quest-table" id="studentChapterExercisesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:20%"><?= $translator->translate('student.quest.chapter.field.exercise') ?></th>
                                <th style="width:20%"><?= $translator->translate('student.quest.chapter.field.topic') ?></th>
                                <th style="width:15%"><?= $translator->translate('student.quest.chapter.field.type') ?></th>
                                <th style="width:10%">XP</th>
                                <th style="width:10%"><?= $translator->translate('student.quest.chapter.field.action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $exercise): ?>
                                <?php
                                $translatedExerciseType = trim((string) ($exercise['tipo_en'] ?? ''));
                                $displayExerciseType = $useEnglishDbTranslations && $translatedExerciseType !== ''
                                    ? $translatedExerciseType
                                    : (string) ($exercise['tipo'] ?? '');
                                ?>
                                <tr<?= ((int) ($exercise['valutato'] ?? 0) === 1) ? ' class="quest-row-evaluated"' : '' ?>>
                                    <td><?= (int) ($exercise['progressivo'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($exercise['nome_argomento'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($displayExerciseType) ?></td>
                                    <td class="xp-cell"><?php $xp = (int) ($exercise['xp_display'] ?? 0);
                                    if ($xp === 1 || $xp === 0) {
                                        echo $translator->translate('student.quest.chapter.level');
                                    } else {
                                        echo $xp;
                                    } ?> XP</td>
                                    <td class="text-center">
                                        <a href="<?= htmlspecialchars((string) ($exercise['detail_url'] ?? '#')) ?>" class="quest-enter-btn btn btn-sm <?= htmlspecialchars((string) ($exercise['button_class'] ?? 'quest-active')) ?> <?= !empty($exercise['is_locked']) ? 'disabled' : '' ?>" <?= !empty($exercise['is_locked']) ? 'aria-disabled="true"' : '' ?>>
                                            <i class="fas fa-feather"></i>
                                            <?= htmlspecialchars((string) ($exercise['button_text'] ?? $translator->translate('student.quest.chapter.open'))) ?>
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
