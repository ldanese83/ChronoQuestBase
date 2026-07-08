<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$punishments = $punishments ?? [];
?>

<div class="container-fluid">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) $classroom['colore']) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) $classroom['icona']) ?>"></i>
                </div>
                <h1 class="class-title">
                    <?= $translator->translate('student.punishments.class') ?> <?= htmlspecialchars((string) $classroom['nome_classe']) ?> <?= htmlspecialchars((string) $classroom['anno_scolastico']) ?>
                </h1>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($punishments === []): ?>
        <div class="alert alert-success"><strong><?= $translator->translate('student.punishments.congrats') ?></strong> <?= $translator->translate('student.punishments.empty') ?></div>
    <?php else: ?>
        <?php foreach ($punishments as $punishment): ?>
            <?php $punishmentId = (int) ($punishment['fk_punizione'] ?? 0); ?>
            <div class="punishment-card">
                <div class="punishment-header">
                    <i class="fas fa-skull-crossbones"></i>
                    <?= $translator->translate('student.punishments.additional_tasks') ?>
                </div>

                <div class="punishment-deadline">
                    ⏳ <?= $translator->translate('student.punishments.deadline') ?>:
                    <?php if (!empty($punishment['data_scadenza'])): ?>
                        <?= htmlspecialchars((string) date('d/m/Y', strtotime((string) $punishment['data_scadenza']))) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>

                <?php if (!empty($punishment['img_punizione'])): ?>
                    <img class="punishment-img" src="<?= htmlspecialchars((string) $punishment['img_punizione']) ?>" alt="<?= htmlspecialchars($translator->translate('student.punishments.alt.punishment')) ?>">
                <?php endif; ?>

                <div class="punishment-desc">
                    <?= htmlspecialchars_decode(html_entity_decode((string) ($punishment['descrizione_punizione'] ?? ''))) ?>
                </div>

                <div class="punishment-separator"></div>

                <h4 class="text-center fw-bold" style="color:#ff9a9a;">
                    📜 <?= $translator->translate('student.punishments.delivery_title') ?>
                </h4>

                <form action="/studenti/punizioni/consegna" class="dropzone dropzone_pun js-punishment-dropzone" id="dropzoneArea<?= $punishmentId ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id_punizione" value="<?= $punishmentId ?>">
                </form>

                <div id="responseContainer<?= $punishmentId ?>" class="punishment-response"></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
