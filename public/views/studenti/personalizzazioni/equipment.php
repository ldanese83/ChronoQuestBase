<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$equipment = $equipment ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <div class="class-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon"><i class="fa-solid <?= htmlspecialchars((string) ($classroom['icona'] ?? 'fa-user')) ?>"></i></div>
                <h1 class="class-title"><?= $translator->translate('student.customization.class') ?> <?= htmlspecialchars((string) (($classroom['nome_classe'] ?? '') . ' ' . ($classroom['anno_scolastico'] ?? ''))) ?></h1>
            </div>
            <div class="class-actions d-flex gap-2"><a href="/studenti/personalizzazioni" class="action-btn back"><i class="fas fa-arrow-left"></i><span><?= $translator->translate('student.customization.back') ?></span></a></div>
        </div>
    </div>

    <div class="equipment-header"><h1><?= $translator->translate('student.customization.equipment.title') ?></h1><p><?= $translator->translate('student.customization.equipment.subtitle') ?></p></div>
    <div class="shop-wallet"><i class="fas fa-coins"></i><?= (int) ($student['monete'] ?? 0) ?> <?= $translator->translate('student.customization.available_coins') ?></div>

    <form id="buyEquipmentForm" method="post" action="/studenti/personalizzazioni/equipaggiamento/buy"><input type="hidden" id="buy_equipment_id" name="id_equip" value="0"></form>
    <form id="equipEquipmentForm" method="post" action="/studenti/personalizzazioni/equipaggiamento/equip"><input type="hidden" id="equip_equipment_id" name="id_equip" value="0"></form>
    <form id="sellEquipmentForm" method="post" action="/studenti/personalizzazioni/equipaggiamento/sell"><input type="hidden" id="sell_equipment_id" name="id_equip" value="0"></form>

    <div class="equipment-grid">
        <?php foreach ($equipment as $item): ?>
            <?php $state = (string) ($item['state'] ?? 'locked'); ?>
            <div class="equipment-card <?= $state === 'equipped' ? 'owned equipped' : $state ?>">
                <div class="equipment-image"><img src="<?= htmlspecialchars((string) ($item['img_src'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?>"></div>
                <div class="equipment-info">
                    <h3><?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?></h3>
                    <p class="equipment-desc"><?= htmlspecialchars((string) ($item['descrizione'] ?? '')) ?></p>
                    <ul class="equipment-stats">
                        <?php foreach (($item['abilities'] ?? []) as $ability): ?>
                            <?php
                            $translatedAbilityText = trim((string) ($ability['testo_abilita_en'] ?? ''));
                            $displayAbilityText = $useEnglishDbTranslations && $translatedAbilityText !== ''
                                ? $translatedAbilityText
                                : (string) ($ability['testo_abilita'] ?? '');
                            ?>
                            <li><span style='padding-left:0.3rem;font-weight:bold;'><?= ($displayAbilityText) ?> +<?= (int) ($ability['aumento'] ?? 0) ?><?= in_array((string) ($ability['tipologia'] ?? ''), ['monete', 'esperienza'], true) ? '%' : '' ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="equipment-action">
                    <?php if ($state === 'locked'): ?>
                        <button type="button" class="btn btn-warning" onclick="acquistaEquip(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>,<?= (int) ($item['costo'] ?? 0) ?>)"><i class="fas fa-coins"></i> <?= (int) ($item['costo'] ?? 0) ?></button>
                    <?php elseif ($state === 'owned'): ?>
                        <button type="button" class="btn btn-equip" onclick="equipaggia(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>)"><i class="fas fa-hand-sparkles"></i> <?= $translator->translate('student.customization.equip') ?></button>
                        <button type="button" class="btn btn-sell-equipment" onclick="vendiEquip(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>)"><i class="fas fa-coins"></i> <?= $translator->translate('student.customization.sell') ?> (+<?= (int) ($item['refund'] ?? 0) ?>)</button>
                    <?php else: ?>
                        <button class="btn btn-equipped" disabled><i class="fas fa-check"></i> <?= $translator->translate('student.customization.equipped') ?></button>
                        <button type="button" class="btn btn-sell-equipment" onclick="vendiEquip(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>)"><i class="fas fa-coins"></i> <?= $translator->translate('student.customization.sell') ?> (+<?= (int) ($item['refund'] ?? 0) ?>)</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
