<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$classroom = $classroom ?? null;
$quests = $quests ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="class-header mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) ($classroom['icona'] ?? 'fa-school')) ?>"></i>
                </div>
                <h1 class="class-title">
                    <?= $translator->translate('student.quest.class') ?> <?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </h1>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="text-center mb-4" style="text-shadow: 0 0 20px #ffcc88;"><i class="fas fa-scroll"></i> <?= $translator->translate('student.quest.index.available') ?></h1>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-6 search-box">
                        <input id="searchInput" type="text" class="form-control form-control-lg" placeholder="<?= htmlspecialchars($translator->translate('student.quest.index.search_placeholder')) ?>">
                    </div>
                </div>
            </div>

            <div id="questsContainer" class="row g-4 p-3">
                <?php foreach ($quests as $quest): ?>
                    <div class="col-md-4 quest-item" data-name="<?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?>">
                        <div class="quest-card">
                            <div class="portal" onclick="window.location.href='/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina';"></div>

                            <img class="quest-img" src="<?= htmlspecialchars((string) ($quest['image_quest'] ?? '')) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.quest.alt.quest'), (string) ($quest['nome_quest'] ?? ''))) ?>">

                            <div class="quest-name"><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></div>

                            <button class="access-btn" onclick="window.location.href='/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina';">
                                <i class="fa-solid fa-door-open"></i> <?= $translator->translate('student.quest.index.enter') ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
