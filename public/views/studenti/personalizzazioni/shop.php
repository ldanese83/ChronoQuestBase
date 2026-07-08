<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$shopSections = $shopSections ?? [];
$sconto_attivo = (int) ($sconto_attivo ?? 0);
$motivazioni_sconti = $motivazioni_sconti ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
$sectionMeta = [
    'Sfondo' => ['title_key' => 'student.customization.shop.section.avatar_backgrounds', 'class' => 'shop-bg'],
    'BigBackground' => ['title_key' => 'student.customization.shop.section.character_backgrounds', 'class' => 'shop-big-bg'],
    'Capelli' => ['title_key' => 'student.customization.shop.section.hair_color', 'class' => 'shop-hair'],
    'Pet' => ['title_key' => 'student.customization.shop.section.pet', 'class' => 'shop-pet'],
];
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

    <div class="shop-header">&#127984; <?= $translator->translate('student.customization.shop.welcome') ?></div>
    <div class="shop-wallet"><i class="fas fa-coins"></i><?= (int) ($student['monete'] ?? 0) ?> <?= $translator->translate('student.customization.available_coins') ?></div>

    <?php if ($sconto_attivo > 0): ?>
        <div class="shop-discount-banner">
            <div class="shop-discount-title">&#127881; <?= sprintf($translator->translate('student.customization.shop.discount_today'), $sconto_attivo) ?></div>
            <?php foreach ($motivazioni_sconti as $motivazione): ?>
                <div class="shop-discount-motivazione"><?= htmlspecialchars((string) $motivazione) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="buyCustomizationForm" method="post" action="/studenti/personalizzazioni/negozio/buy">
        <input type="hidden" id="buy_customization_id" name="id_personalizzazione" value="0">
    </form>

    <?php foreach ($sectionMeta as $type => $meta): ?>
        <div class="shop-section-title <?= htmlspecialchars($meta['class']) ?>"><?= $translator->translate($meta['title_key']) ?></div>
        <div class="shop-grid">
            <?php foreach (($shopSections[$type] ?? []) as $item): ?>
                <?php
                $price = (int) ($item['costo_finale'] ?? 0);
                $locked = (int) ($student['monete'] ?? 0) < $price;
                ?>
                <div class="shop-item <?= $locked ? 'locked' : '' ?>" onclick="<?= $locked ? 'return false;' : ('acquista(' . (int) ($item['id_personalizzazione'] ?? 0) . ',\'' . htmlspecialchars((string) ($item['nome_personalizzazione'] ?? ''), ENT_QUOTES) . '\',' . $price . ')') ?>">
                    <div class="shop-item-header"><?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?></div>
                    <div class="shop-item-body">
                        <img src="<?= htmlspecialchars((string) ($item['img_src'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?>">
                        <?php if (($item['abilities'] ?? []) !== []): ?>
                            <ul class="shop-item-abilities">
                                <?php foreach (($item['abilities'] ?? []) as $ability): ?>
                                    <?php
                                    $translatedAbilityText = trim((string) ($ability['testo_abilita_en'] ?? ''));
                                    $displayAbilityText = $useEnglishDbTranslations && $translatedAbilityText !== ''
                                        ? $translatedAbilityText
                                        : (string) ($ability['testo_abilita'] ?? '');
                                    ?>
                                    <li><?= ($displayAbilityText) ?> +<?= (int) ($ability['aumento'] ?? 0) ?><?= in_array((string) ($ability['tipologia'] ?? ''), ['probabilita_forziere_epico', 'probabilita_forziere_leggendario'], true) ? '%' : '' ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="shop-item-cost">
                            <?php if ($sconto_attivo > 0): ?><span class="shop-item-cost-original"><?= (int) ($item['costo'] ?? 0) ?></span><?php endif; ?>
                            <?= $price ?> <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <?php
    $studentUploadCost = 800;
    $uploadLocked = (int) ($student['monete'] ?? 0) < $studentUploadCost;
    ?>
    <div class="shop-section-title shop-custom"><?= $translator->translate('student.customization.shop.upload.title') ?></div>
    <div class="shop-upload-description">
        <?= $translator->translate('student.customization.shop.upload.description') ?>
    </div>
    <div class="shop-grid">
        <button
            type="button"
            class="shop-item shop-item-button <?= $uploadLocked ? 'locked' : '' ?>"
            <?= $uploadLocked ? 'disabled' : 'data-bs-toggle="modal" data-bs-target="#studentCustomizationUploadModal"' ?>
        >
            <span class="shop-item-header"><?= $translator->translate('student.customization.shop.upload.card_title') ?></span>
            <span class="shop-item-body">
                <img src="/assets/images/personalizzazioni/puntodomanda.jpg" alt="<?= htmlspecialchars($translator->translate('student.customization.shop.upload.card_title')) ?>">
                <span class="shop-item-cost"><?= $studentUploadCost ?> <i class="fas fa-coins"></i></span>
            </span>
        </button>
    </div>

    <div class="modal fade" id="studentCustomizationUploadModal" tabindex="-1" aria-labelledby="studentCustomizationUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="/studenti/personalizzazioni/negozio/upload" enctype="multipart/form-data" id="studentCustomizationUploadForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentCustomizationUploadModalLabel"><?= $translator->translate('student.customization.shop.upload.modal_title') ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('student.customization.shop.upload.close')) ?>"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="studentCustomizationName" class="form-label"><?= $translator->translate('student.customization.shop.upload.name') ?></label>
                            <input type="text" class="form-control" id="studentCustomizationName" name="nome_personalizzazione" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentCustomizationImage" class="form-label"><?= $translator->translate('student.customization.shop.upload.image') ?></label>
                            <input type="file" class="form-control" id="studentCustomizationImage" name="immagine_personalizzazione" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif" required>
                            <div class="form-text"><?= $translator->translate('student.customization.shop.upload.image_help') ?></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $translator->translate('student.customization.shop.upload.cancel') ?></button>
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('student.customization.shop.upload.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
