<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$quest = $quest ?? null;
$chapters = $chapters ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
        <div class="quest-map-header mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="quest-map-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h1 class="quest-map-title"><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></h1>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/problemi" class="quest-back-btn">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $translator->translate('student.quest.problems.open') ?>
                </a>
                <a href="/studenti/quest" class="quest-back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <?= $translator->translate('student.quest.map.back_to_quests') ?>
                </a>
            </div>
        </div>

        <div id="piantina" class="quest-map-wrapper mb-4">
            <div class="quest-map-frame">
                <canvas id="immagine_piantina" width="1000" height="666" data-map-src="<?= htmlspecialchars((string) ($quest['piantina_quest'] ?? '')) ?>"></canvas>
            </div>
        </div>

        <script>
            window.studentQuestMapData = {
                chapters: <?= json_encode($chapters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
            };
        </script>
    <?php endif; ?>
</div>
