<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$activeMonster = is_array($activeMonster ?? null) ? $activeMonster : null;
$lastLogs = is_array($lastLogs ?? null) ? $lastLogs : [];
$setBonus = is_array($setBonus ?? null) ? $setBonus : null;
$defeatModal = is_array($defeatModal ?? null) ? $defeatModal : null;
$translateItemType = static function (string $type) use ($translator): string {
    $translated = $translator->translate('plugin.ftm.item_type.' . $type);
    return $translated !== '' ? $translated : $type;
};
?>
<div class="container-fluid ftm-page">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-100"><?= $translator->translate('plugin.ftm.student.title') ?></h1>
        <a class="btn btn-warning" href="/studenti/plugin/fight-the-monster/armi">
            <i class="fas fa-shield-alt me-1"></i><?= $translator->translate('plugin.ftm.student.items') ?>
        </a>
    </div>

    <div class="ftm-monster-frame">
        <?php if ($activeMonster === null): ?>
            <h2><?= $translator->translate('plugin.ftm.no_active_monster') ?></h2>
        <?php else: ?>
            <?php $hpPercent = max(0, min(100, ((int) $activeMonster['hp_attuali'] / max(1, (int) $activeMonster['hp_massimi'])) * 100)); ?>
            <img class="ftm-student-monster-image" src="<?= htmlspecialchars((string) ($activeMonster['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($activeMonster['nome_mostro'] ?? '')) ?>">
            <h2><?= htmlspecialchars((string) ($activeMonster['nome_mostro'] ?? '')) ?></h2>
            <div class="ftm-hp"><span style="width: <?= $hpPercent ?>%"></span></div>
            <p><?= (int) $activeMonster['hp_attuali'] ?> / <?= (int) $activeMonster['hp_massimi'] ?> HP</p>
            <p class="ftm-powers">
                <?= $translator->translate('plugin.ftm.evil_powers') ?>:
                -<?= (int) $activeMonster['danno_cuori'] ?> <?= $translator->translate('plugin.ftm.hearts') ?>,
                -<?= (int) $activeMonster['danno_mana'] ?> <?= $translator->translate('plugin.ftm.mana') ?>,
                -<?= (int) $activeMonster['danno_monete'] ?> <?= $translator->translate('plugin.ftm.coins') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.student.last_turn') ?></h6></div>
        <div class="card-body">
            <?php if ($lastLogs === []): ?>
                <p class="mb-0"><?= htmlspecialchars($translator->translate('plugin.ftm.student.no_events')) ?></p>
            <?php else: ?>
                <?php foreach ($lastLogs as $log): ?>
                    <p class="mb-2"><?= htmlspecialchars((string) ($log['testo'] ?? '')) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($setBonus !== null): ?>
        <div class="alert alert-warning mt-4">
            <?= sprintf($translator->translate('plugin.ftm.student.full_set'), htmlspecialchars((string) ($setBonus['nome_eroe'] ?? '')), (int) ($setBonus['bonus_set_terzo_forziere'] ?? 0)) ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($defeatModal !== null): ?>
    <?php $modalMonster = is_array($defeatModal['monster'] ?? null) ? $defeatModal['monster'] : []; ?>
    <?php $modalItems = is_array($defeatModal['items'] ?? null) ? $defeatModal['items'] : []; ?>
    <?php $modalDropIds = is_array($defeatModal['dropIds'] ?? null) ? $defeatModal['dropIds'] : []; ?>
    <div class="modal fade ftm-defeat-modal" id="ftmDefeatModal" tabindex="-1" aria-labelledby="ftmDefeatModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" method="post" action="/studenti/plugin/fight-the-monster/drop-visti">
                <?php foreach ($modalDropIds as $dropId): ?>
                    <input type="hidden" name="drop_ids[]" value="<?= (int) $dropId ?>">
                <?php endforeach; ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="ftmDefeatModalLabel"><?= $translator->translate('plugin.ftm.student.monster_defeated_title') ?></h5>
                </div>
                <div class="modal-body">
                    <div class="ftm-defeat-monster">
                        <img src="<?= htmlspecialchars((string) ($modalMonster['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($modalMonster['nome_mostro'] ?? '')) ?>">
                        <i class="fas fa-skull ftm-defeat-skull" aria-hidden="true"></i>
                    </div>
                    <p class="ftm-defeat-message">
                        <?= htmlspecialchars(sprintf($translator->translate('plugin.ftm.student.monster_defeated_message'), (string) ($modalMonster['nome_mostro'] ?? ''))) ?>
                    </p>
                    <div class="ftm-drop-grid">
                        <?php foreach ($modalItems as $item): ?>
                            <div class="ftm-drop-card">
                                <img src="<?= htmlspecialchars((string) ($item['immagine_item'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['nome_item'] ?? '')) ?>">
                                <strong><?= htmlspecialchars((string) ($item['nome_item'] ?? '')) ?></strong>
                                <span><?= htmlspecialchars((string) ($item['nome_eroe'] ?? '')) ?> - <?= htmlspecialchars($translateItemType((string) ($item['tipologia'] ?? ''))) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="submit"><?= $translator->translate('plugin.ftm.student.monster_defeated_confirm') ?></button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.bootstrap && window.bootstrap.Modal) {
                var modalElement = document.getElementById('ftmDefeatModal');
                if (modalElement) {
                    window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            }
        });
    </script>
<?php endif; ?>
