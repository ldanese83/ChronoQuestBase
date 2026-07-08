<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$character = $character ?? null;
$activeByType = $activeByType ?? [];
$ownedByType = $ownedByType ?? [];
$hasAnyCustomization = (bool) ($hasAnyCustomization ?? false);
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>

<div class="container-fluid">
    <div class="class-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon"><i class="fa-solid <?= htmlspecialchars((string) ($classroom['icona'] ?? 'fa-user')) ?>"></i></div>
                <h1 class="class-title"><?= $translator->translate('student.customization.class') ?> <?= htmlspecialchars((string) (($classroom['nome_classe'] ?? '') . ' ' . ($classroom['anno_scolastico'] ?? ''))) ?></h1>
            </div>
            <div class="class-actions d-flex gap-2">
                <a href="/studenti/personalizzazioni/negozio" class="action-btn shop">
                    <i class="fas fa-store"></i>
                    <span><?= $translator->translate('student.customization.shop') ?></span>
                </a>
                <a href="/studenti/personalizzazioni/equipaggiamento" class="action-btn equipment">
                    <i class="fas fa-shield-alt"></i>
                    <span><?= $translator->translate('student.customization.equipment') ?></span>
                </a>
            </div>
        </div>
    </div>

    <?php if (!$hasAnyCustomization): ?>
        <div class="no-customization-box">
            <div class="no-customization-message">
                <i class="fas fa-hat-wizard"></i>
                <h2><?= $translator->translate('student.customization.empty.title') ?></h2>
                <p><?= $translator->translate('student.customization.empty.message') ?></p>
            </div>
            <a href="/studenti/personalizzazioni/negozio" class="go-shop-btn"><i class="fas fa-store"></i> <?= $translator->translate('student.customization.go_to_shop') ?></a>
            <div class="wallet-box">
                <i class="fas fa-coins"></i>
                <span class="wallet-amount"><?= (int) ($student['monete'] ?? 0) ?></span>
                <span class="wallet-label"><?= $translator->translate('student.customization.available_coins') ?></span>
            </div>
        </div>
    <?php else: ?>
        <div class="personalization-preview">
            <div class="personalization-avatar-frame" style="border:1px solid <?= htmlspecialchars((string) ($character['bordercolor'] ?? '#ddd')) ?>;box-shadow:2px 2px 4px 2px <?= htmlspecialchars((string) ($character['color'] ?? '#999')) ?>;">
                <img class="personalization-layer personalization-layer-bg" id="sfondo_pers" src="<?= htmlspecialchars((string) (($activeByType['Sfondo']['img_src'] ?? ''))) ?>" alt="">
                <img class="personalization-layer personalization-layer-base" src="<?= htmlspecialchars((string) ('/' . ltrim((string) ($character['img_senza_sfondo'] ?? ''), '/'))) ?>" alt="">
                <img class="personalization-layer personalization-layer-hair" id="capelli_pers" src="<?= htmlspecialchars((string) (($activeByType['Capelli']['img_src'] ?? ''))) ?>" alt="">
                <img class="personalization-layer personalization-layer-custom" id="personale_pers" src="<?= htmlspecialchars((string) (($activeByType['Personale']['img_src'] ?? ''))) ?>" alt="" <?= empty($activeByType['Personale']['img_src']) ? 'style="display:none"' : '' ?>>
            </div>
            <div class="personalization-avatar-frame" style="border:1px solid <?= htmlspecialchars((string) ($character['bordercolor'] ?? '#ddd')) ?>;box-shadow:2px 2px 4px 2px <?= htmlspecialchars((string) ($character['color'] ?? '#999')) ?>;margin-left:1vw;">
                <img class="personalization-layer personalization-layer-bg" id="big_sfondo" src="<?= htmlspecialchars((string) (($activeByType['BigBackground']['img_src'] ?? ''))) ?>" alt="">
            </div>
            <div class="personalization-avatar-frame" style="border:1px solid <?= htmlspecialchars((string) ($character['bordercolor'] ?? '#ddd')) ?>;box-shadow:2px 2px 4px 2px <?= htmlspecialchars((string) ($character['color'] ?? '#999')) ?>;margin-left:1vw;">
                <img class="personalization-layer personalization-layer-bg" id="pet" src="<?= htmlspecialchars((string) (($activeByType['Pet']['img_src'] ?? ''))) ?>" alt="">
            </div>

            <form id="personalizationForm" method="post" action="/studenti/personalizzazioni/save">
                <input type="hidden" id="sfondo_scelto" name="sfondo_scelto" value="<?= (int) ($activeByType['Sfondo']['id_personalizzazione'] ?? 0) ?>">
                <input type="hidden" id="bigsfondo_scelto" name="bigsfondo_scelto" value="<?= (int) ($activeByType['BigBackground']['id_personalizzazione'] ?? 0) ?>">
                <input type="hidden" id="capelli_scelti" name="capelli_scelti" value="<?= (int) ($activeByType['Capelli']['id_personalizzazione'] ?? 0) ?>">
                <input type="hidden" id="personale_scelto" name="personale_scelto" value="<?= (int) ($activeByType['Personale']['id_personalizzazione'] ?? 0) ?>">
                <input type="hidden" id="id_pet" name="id_pet" value="<?= (int) ($activeByType['Pet']['id_personalizzazione'] ?? 0) ?>">
            </form>

            <form id="sellCustomizationForm" method="post" action="/studenti/personalizzazioni/sell">
                <input type="hidden" id="sell_customization_id" name="id_personalizzazione" value="0">
            </form>
        </div>

        <div class="personalization-save-box">
            <button type="button" class="personalization-save-btn" onclick="salva_personalizzazione()">
                <i class="fas fa-save"></i> <?= $translator->translate('student.customization.save') ?>
            </button>
        </div>

        <?php
        $sections = [
            'Sfondo' => ['title_key' => 'student.customization.section.avatar_backgrounds', 'class' => 'fantasy-bg', 'fn' => 'cambia_sfondo'],
            'BigBackground' => ['title_key' => 'student.customization.section.character_backgrounds', 'class' => 'fantasy-big-bg', 'fn' => 'cambia_bigsfondo'],
            'Capelli' => ['title_key' => 'student.customization.section.hair_color', 'class' => 'fantasy-hair', 'fn' => 'cambia_capelli'],
            'Personale' => ['title_key' => 'student.customization.section.personal_images', 'class' => 'fantasy-custom', 'fn' => 'cambia_personale'],
            'Pet' => ['title_key' => 'student.customization.section.pet', 'class' => 'fantasy-pet', 'fn' => 'cambia_pet'],
        ];
        ?>

        <?php foreach ($sections as $type => $meta): ?>
            <h3 class="personalization-section-title <?= htmlspecialchars($meta['class']) ?>"><i class="fas fa-layer-group"></i> <?= $translator->translate($meta['title_key']) ?></h3>
            <div class="personalization-grid">
                <div class="personalization-item" onclick="<?= $meta['fn'] ?>(0,'')">
                    <img src="/assets/images/divieto.png" alt="<?= htmlspecialchars($translator->translate('student.customization.none')) ?>">
                    <span><?= $translator->translate('student.customization.none') ?></span>
                </div>
                <?php foreach (($ownedByType[$type] ?? []) as $item): ?>
                    <div class="personalization-item" onclick="<?= $meta['fn'] ?>(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>,'<?= htmlspecialchars((string) ($item['img_src'] ?? ''), ENT_QUOTES) ?>')">
                        <img src="<?= htmlspecialchars((string) ($item['img_src'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?>">
                        <span><?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?></span>
                        <button type="button" class="personalization-sell-btn" onclick="event.stopPropagation();vendi_personalizzazione(<?= (int) ($item['id_personalizzazione'] ?? 0) ?>)"><?= $translator->translate('student.customization.sell') ?> (+<?= (int) ($item['refund'] ?? 0) ?>)</button>
                        <?php if (($item['abilities'] ?? []) !== []): ?>
                            <ul class="personalization-abilities">
                                <?php foreach (($item['abilities'] ?? []) as $ability): ?>
                                    <?php
                                    $translatedAbilityText = trim((string) ($ability['testo_abilita_en'] ?? ''));
                                    $displayAbilityText = $useEnglishDbTranslations && $translatedAbilityText !== ''
                                        ? $translatedAbilityText
                                        : (string) ($ability['testo_abilita'] ?? '');
                                    ?>
                                    <li><?= htmlspecialchars($displayAbilityText) ?> +<?= (int) ($ability['aumento'] ?? 0) ?><?= in_array((string) ($ability['tipologia'] ?? ''), ['probabilita_forziere_epico', 'probabilita_forziere_leggendario'], true) ? '%' : '' ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
