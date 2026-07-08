<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$availablePowerTiers = $availablePowerTiers ?? [];
$canChoosePower = (bool) ($canChoosePower ?? false);
$studentLevel = (int) ($student['livello'] ?? 0);
$pendingChoices = (int) ($student['pot_da_scegliere'] ?? 0);
?>

<div class="container-fluid">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) $classroom['colore']) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) $classroom['icona']) ?>"></i>
                </div>
                <h1 class="class-title">
                    <?= $translator->translate('student.powers.class') ?> <?= htmlspecialchars((string) $classroom['nome_classe']) ?> <?= htmlspecialchars((string) $classroom['anno_scolastico']) ?>
                </h1>
            </div>
        </div>
    <?php endif; ?>

    <div class="power-status-wrapper mb-4">
        <?php if ($pendingChoices > 0): ?>
            <div class="power-status success">
                <div class="power-status-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="power-status-text">
                    <h2><?= $translator->translate('student.powers.add.new_available') ?> <span>(<?= $pendingChoices ?>)</span></h2>
                    <p><?= $translator->translate('student.powers.add.energy_grown') ?> <strong><?= $translator->translate('student.powers.add.choose_wisely') ?></strong></p>
                </div>
            </div>
        <?php else: ?>
            <div class="power-status locked">
                <div class="power-status-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="power-status-text">
                    <h2><?= $translator->translate('student.powers.add.none_available') ?></h2>
                    <p><?= $translator->translate('student.powers.add.keep_leveling') ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php foreach ($availablePowerTiers as $tier): ?>
        <?php
        $powers = is_array($tier['powers'] ?? null) ? $tier['powers'] : [];
        $tierName = (string) ($tier['label'] ?? $translator->translate('student.powers.tier.default'));
        $tierClass = (string) ($tier['class'] ?? 'bronze-box');
        $badgeText = (string) ($tier['badge'] ?? $translator->translate('student.powers.badge.default'));
        $badgeClass = (string) ($tier['rarity'] ?? 'bronze');
        ?>
        <div class="card shadow mb-4" style="width:100%;">
            <div class="card-header py-3 <?= htmlspecialchars($tierClass) ?>">
                <h6 class="m-0 font-weight-bold" style="font-size:1.6rem;"><?= htmlspecialchars($tierName) ?></h6>
            </div>
            <div class="card-body" style="width:100%;">
                <div class="table-responsive" style="width:100%;">
                    <table class="table table-bordered table_choose_power" style="width:100%;" cellspacing="0">
                        <thead>
                        <tr class="power-row">
                            <th style="width:20%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.image') ?></th>
                            <th style="width:8%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.required_level') ?></th>
                            <th style="width:17%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.name') ?></th>
                            <th style="width:35%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.description') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.mana_cost') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('student.powers.add.table.choose') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($powers === []): ?>
                            <tr>
                                <td colspan="6" class="text-center"><?= $translator->translate('student.powers.add.none_in_tier') ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($powers as $power): ?>
                                <?php
                                $powerLevel = (int) ($power['livello'] ?? 0);
                                $isLocked = $powerLevel > $studentLevel;
                                $canSelect = !$isLocked && $canChoosePower;
                                ?>
                                <tr class="<?= $isLocked ? 'power-row-locked' : '' ?> <?= $canSelect ? 'power-row-available' : '' ?>">
                                    <td class="power-img-cell">
                                        <img src="<?= htmlspecialchars((string) ($power['img_src'] ?? '')) ?>" class="power-thumb" alt="<?= htmlspecialchars((string) ($power['nome_potere'] ?? $translator->translate('student.powers.power'))) ?>">
                                    </td>
                                    <td class="power-level">
                                        <i class="fas fa-star"></i> <?= $powerLevel ?>
                                    </td>
                                    <td class="power-name2">
                                        <?php
                                        if($_SESSION['lang']=='it' or $power['nome_potere_en']==NULL)
                                            echo htmlspecialchars_decode(html_entity_decode((string) ($power['nome_potere'] ?? $translator->translate('student.powers.power')))); 
                                        else if($_SESSION['lang']=='en')
                                            echo htmlspecialchars_decode(html_entity_decode((string) ($power['nome_potere_en'] ?? $translator->translate('student.powers.power')))); ?>
                                        <span class="rarity-badge <?= htmlspecialchars($badgeClass) ?>"><?= htmlspecialchars($badgeText) ?></span>
                                    </td>
                                    <td class="power-desc2">
                                        <?php 
                                        if($_SESSION['lang']=='it' or $power['descrizione_potere_en']==NULL)
                                            echo htmlspecialchars_decode(html_entity_decode((string) ($power['descrizione_potere'] ?? '')));
                                        else if($_SESSION['lang']=='en')
                                            echo htmlspecialchars_decode(html_entity_decode((string) ($power['descrizione_potere_en'] ?? '')));
                                        ?></td>
                                    <td class="power-mana2">
                                        <i class="fas fa-yin-yang"></i>
                                        <?= (int) ($power['mana_necessario'] ?? 0) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($canSelect): ?>
                                            <form method="post" action="/studenti/poteri/aggiungi" onsubmit="return confirm(window.cqT('student.powers.confirm.choose', <?= htmlspecialchars(json_encode($translator->translate('student.powers.confirm.choose'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>));">
                                                <input type="hidden" name="power_id" value="<?= (int) ($power['id_potere'] ?? 0) ?>">
                                                <button type="submit" class="power-choose-btn">
                                                    <i class="fas fa-hand-sparkles"></i> <?= $translator->translate('student.powers.choose') ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
