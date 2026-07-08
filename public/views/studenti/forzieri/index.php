<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$classroom = $classroom ?? null;
$raritaCards = $raritaCards ?? [];
$forzieri = $forzieri ?? [];
$nextChestDeliveriesMissing = (int) ($nextChestDeliveriesMissing ?? 3);
$rewardModal = $rewardModal ?? ['type' => '', 'amount' => '', 'name' => '', 'img' => ''];
?>

<?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
<div class="container-fluid">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) ($classroom['colore'] ?? '#1f2937')) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) ($classroom['icona'] ?? 'fa-school')) ?>"></i>
                </div>
                <h1 class="class-title"><?= $translator->translate('student.chests.class') ?> <?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?></h1>
            </div>
        </div>
    <?php endif; ?>

    <div class="forzieri-hero mb-4">
        <h2><?= $translator->translate('student.chests.title') ?></h2>
        <p><?= $translator->translate('student.chests.subtitle') ?></p>
    </div>

    <div class="alert alert-info forzieri-progress-alert mb-4" role="alert">
        <i class="fas fa-box-open"></i>
        <span>
            <?= htmlspecialchars(sprintf(
                $translator->translate($nextChestDeliveriesMissing === 1 ? 'student.chests.next.single' : 'student.chests.next.plural'),
                $nextChestDeliveriesMissing
            )) ?>
        </span>
    </div>

    <div class="forzieri-section-title"><?= $translator->translate('student.chests.section.rarities') ?></div>
    <div class="forzieri-grid mb-5">
        <?php foreach ($raritaCards as $card): ?>
            <div class="card forziere-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="forziere-badge <?= htmlspecialchars((string) ($card['class'] ?? '')) ?>">
                        <?= htmlspecialchars((string) ($card['label'] ?? '')) ?>
                    </span>
                </div>
                <div class="text-center">
                    <img src="<?= htmlspecialchars((string) ($card['img'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($card['titolo'] ?? $translator->translate('student.chests.chest'))) ?>" class="img-fluid mb-3" />
                </div>
                <h5 class="mb-2"><?= htmlspecialchars((string) ($card['titolo'] ?? '')) ?></h5>
                <p class="mb-0"><?= htmlspecialchars((string) ($card['descrizione'] ?? '')) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="forzieri-section-title"><?= $translator->translate('student.chests.section.to_open') ?></div>
    <?php if ($forzieri === []): ?>
        <div class="forziere-empty">
            <?= $translator->translate('student.chests.empty') ?>
        </div>
    <?php else: ?>
        <div class="forzieri-grid mb-4">
            <?php foreach ($forzieri as $forziere): ?>
                <div class="forziere-open-card">
                    <img src="<?= htmlspecialchars((string) ($forziere['img'] ?? '')) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.chests.alt.chest'), (string) ($forziere['rarity_label'] ?? $forziere['livello_rarita'] ?? ''))) ?>" />
                    <div class="mb-2">
                        <span class="forziere-badge <?= htmlspecialchars((string) ($forziere['badge_class'] ?? '')) ?>">
                            <?= htmlspecialchars((string) ($forziere['rarity_label'] ?? $forziere['livello_rarita'] ?? '')) ?>
                        </span>
                    </div>
                    <form action="/studenti/forzieri/apri" method="post">
                        <input type="hidden" name="id_forziere" value="<?= (int) ($forziere['id_forziere'] ?? 0) ?>" />
                        <button class="btn btn-warning btn-sm" type="submit"><?= $translator->translate('student.chests.open') ?></button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (($rewardModal['type'] ?? '') !== '' && ($rewardModal['img'] ?? '') !== ''): ?>
<div class="modal fade" id="rewardModal" tabindex="-1" role="dialog" aria-labelledby="rewardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rewardModalLabel"><?= $translator->translate('student.chests.reward.title') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('student.chests.close')) ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid mb-3" style="max-height:200px" src="<?= htmlspecialchars((string) ($rewardModal['img'] ?? '')) ?>" alt="<?= htmlspecialchars($translator->translate('student.chests.reward.alt')) ?>" />
                <?php if (($rewardModal['type'] ?? '') === 'monete'): ?>
                    <p><?= sprintf($translator->translate('student.chests.reward.coins'), '<strong>' . htmlspecialchars((string) ($rewardModal['amount'] ?? '0')) . '</strong>') ?></p>
                <?php elseif (($rewardModal['type'] ?? '') === 'xp'): ?>
                    <p><?= sprintf($translator->translate('student.chests.reward.xp'), '<strong>' . htmlspecialchars((string) ($rewardModal['amount'] ?? '0')) . '</strong>') ?></p>
                <?php else: ?>
                    <p><?= sprintf($translator->translate('student.chests.reward.customization'), '<strong>' . htmlspecialchars((string) ($rewardModal['name'] ?? '')) . '</strong>') ?></p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-bs-dismiss="modal"><?= $translator->translate('student.chests.reward.ok') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
