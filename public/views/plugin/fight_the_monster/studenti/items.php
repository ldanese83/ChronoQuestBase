<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$inventory = is_array($inventory ?? null) ? $inventory : [];
$equipped = is_array($equipped ?? null) ? $equipped : [];
$itemTypes = is_array($itemTypes ?? null) ? $itemTypes : [];
$setBonus = is_array($setBonus ?? null) ? $setBonus : null;
$translateItemType = static function (string $type) use ($translator): string {
    $translated = $translator->translate('plugin.ftm.item_type.' . $type);
    return $translated !== '' ? $translated : $type;
};
?>
<div class="container-fluid ftm-page">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-100"><?= $translator->translate('plugin.ftm.student.items') ?></h1>
        <a class="btn btn-outline-light" href="/studenti/plugin/fight-the-monster"><?= $translator->translate('plugin.ftm.back_to_monster') ?></a>
    </div>

    <div class="ftm-equipment-grid mb-4">
        <?php foreach ($itemTypes as $type): ?>
            <?php $item = is_array($equipped[$type] ?? null) ? $equipped[$type] : null; ?>
            <div class="ftm-slot">
                <h5><?= htmlspecialchars($translateItemType((string) $type)) ?></h5>
                <?php if ($item === null): ?>
                    <p class="text-muted mb-0"><?= $translator->translate('plugin.ftm.student.empty_slot') ?></p>
                <?php else: ?>
                    <img src="<?= htmlspecialchars((string) ($item['immagine'] ?? '')) ?>" alt="">
                    <strong><?= htmlspecialchars((string) ($item['nome_item'] ?? '')) ?></strong>
                    <small><?= htmlspecialchars((string) ($item['nome_eroe'] ?? '')) ?> - <?= (int) ($item['bonus_doppio_forziere'] ?? 0) ?>%</small>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($setBonus !== null): ?>
        <div class="alert alert-warning">
            <?= sprintf($translator->translate('plugin.ftm.student.full_set'), htmlspecialchars((string) ($setBonus['nome_eroe'] ?? '')), (int) ($setBonus['bonus_set_terzo_forziere'] ?? 0)) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.student.inventory') ?></h6></div>
        <div class="card-body">
            <div class="ftm-item-grid">
                <?php foreach ($inventory as $item): ?>
                    <div class="ftm-item-card">
                        <img src="<?= htmlspecialchars((string) ($item['immagine'] ?? '')) ?>" alt="">
                        <strong><?= htmlspecialchars((string) ($item['nome_item'] ?? '')) ?></strong>
                        <span><?= htmlspecialchars((string) ($item['nome_eroe'] ?? '')) ?> - <?= htmlspecialchars($translateItemType((string) ($item['tipologia'] ?? ''))) ?></span>
                        <small><?= sprintf($translator->translate('plugin.ftm.item.double_bonus_value'), (int) ($item['bonus_doppio_forziere'] ?? 0)) ?></small>
                        <?php if ((int) ($item['equipaggiato'] ?? 0) === 1): ?>
                            <span class="badge bg-success"><?= $translator->translate('plugin.ftm.student.equipped') ?></span>
                        <?php else: ?>
                            <form method="post" action="/studenti/plugin/fight-the-monster/armi/<?= (int) ($item['id_student_item'] ?? 0) ?>/equipaggia">
                                <button class="btn btn-sm btn-primary" type="submit"><?= $translator->translate('plugin.ftm.student.equip') ?></button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if ($inventory === []): ?>
                    <p class="text-muted mb-0"><?= $translator->translate('plugin.ftm.student.no_items') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
