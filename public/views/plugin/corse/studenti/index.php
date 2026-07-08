<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$student = is_array($student ?? null) ? $student : [];
$equippedCar = is_array($equippedCar ?? null) ? $equippedCar : null;
$ownedCars = is_array($ownedCars ?? null) ? $ownedCars : [];
$shopCars = is_array($shopCars ?? null) ? $shopCars : [];
$activeRace = is_array($activeRace ?? null) ? $activeRace : null;
$activeRaceResult = is_array($activeRaceResult ?? null) ? $activeRaceResult : null;
$lastRace = is_array($lastRace ?? null) ? $lastRace : null;
$lastRaceResult = is_array($lastRaceResult ?? null) ? $lastRaceResult : null;
$lastRacePrize = is_array($lastRacePrize ?? null) ? $lastRacePrize : null;
$canClaimLastRace = (bool) ($canClaimLastRace ?? false);
$history = is_array($history ?? null) ? $history : [];
$leaderboard = is_array($leaderboard ?? null) ? $leaderboard : [];
$renderCarAbilities = static function (array $car) use ($translator): void {
    $bonusDeliveries = (float) ($car['bonus_consegne'] ?? 0);
    $bonusGrades = (float) ($car['bonus_valutazioni'] ?? 0);
    $rewardBonus = (int) ($car['bonus_ricompense'] ?? 0);
    ?>
    <div class="corse-car-abilities">
        <small><?= $translator->translate('plugin.corse.car.abilities') ?></small>
        <div>
            <?php if ($bonusDeliveries > 0): ?>
                <span>+<?= htmlspecialchars((string) $bonusDeliveries) ?> <?= $translator->translate('plugin.corse.deliveries_short') ?></span>
            <?php endif; ?>
            <?php if ($bonusGrades > 0): ?>
                <span>+<?= htmlspecialchars((string) $bonusGrades) ?> <?= $translator->translate('plugin.corse.grades_short') ?></span>
            <?php endif; ?>
            <?php if ($rewardBonus > 0): ?>
                <span>+<?= $rewardBonus ?>% <?= $translator->translate('plugin.corse.car.reward_bonus_short') ?></span>
            <?php endif; ?>
            <?php if ($bonusDeliveries <= 0 && $bonusGrades <= 0 && $rewardBonus <= 0): ?>
                <span><?= $translator->translate('plugin.corse.car.no_bonus') ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php
};
?>
<div class="container-fluid corse-page">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('plugin.corse.student.title') ?></h1>
        <span class="badge bg-warning text-dark"><?= (int) ($student['monete'] ?? 0) ?> <?= $translator->translate('plugin.corse.coins') ?></span>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.equipped') ?></h6>
                </div>
                <div class="card-body text-center">
                    <?php if ($equippedCar !== null): ?>
                        <img class="corse-equipped-car" src="<?= htmlspecialchars((string) ($equippedCar['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($equippedCar['nome_auto'] ?? '')) ?>">
                        <h4><?= htmlspecialchars((string) ($equippedCar['nome_auto'] ?? '')) ?></h4>
                        <?php $renderCarAbilities($equippedCar); ?>
                    <?php else: ?>
                        <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.no_equipped') ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.active_race') ?></h6>
                </div>
                <div class="card-body">
                    <?php if ($activeRace === null): ?>
                        <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.no_active_race') ?></p>
                    <?php else: ?>
                        <div class="corse-active-race">
                            <h4><?= htmlspecialchars((string) ($activeRace['nome_corsa'] ?? '')) ?></h4>
                            <p class="text-muted">
                                <?= htmlspecialchars((string) ($activeRace['data_inizio'] ?? '')) ?>
                                -
                                <?= htmlspecialchars((string) ($activeRace['data_fine'] ?? '')) ?>
                            </p>
                            <?php if ($activeRaceResult !== null): ?>
                                <div class="corse-prize-items">
                                    <span>
                                        <?= $translator->translate('plugin.corse.position') ?>:
                                        <strong><?= (int) ($activeRaceResult['position'] ?? 0) ?></strong>
                                    </span>
                                    <span>
                                        <?= $translator->translate('plugin.corse.score') ?>:
                                        <strong><?= htmlspecialchars((string) ($activeRaceResult['score'] ?? '0')) ?></strong>
                                    </span>
                                    <span>
                                        <?= $translator->translate('plugin.corse.deliveries') ?>:
                                        <strong><?= htmlspecialchars((string) ($activeRaceResult['avg_deliveries'] ?? '0')) ?></strong>
                                    </span>
                                    <span>
                                        <?= $translator->translate('plugin.corse.grades') ?>:
                                        <strong><?= htmlspecialchars((string) ($activeRaceResult['avg_grades'] ?? '0')) ?></strong>
                                    </span>
                                </div>
                                <small class="text-muted"><?= $translator->translate('plugin.corse.student.active_race_live') ?></small>
                            <?php else: ?>
                                <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.not_ranked') ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.last_race') ?></h6>
                </div>
                <div class="card-body">
                    <?php if ($lastRace === null): ?>
                        <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.no_race') ?></p>
                    <?php else: ?>
                        <h4><?= htmlspecialchars((string) ($lastRace['nome_corsa'] ?? '')) ?></h4>
                        <?php if ($lastRaceResult !== null): ?>
                            <p>
                                <?= $translator->translate('plugin.corse.position') ?>:
                                <strong><?= (int) ($lastRaceResult['posizione'] ?? 0) ?></strong>,
                                <?= $translator->translate('plugin.corse.score') ?>:
                                <strong><?= htmlspecialchars((string) ($lastRaceResult['punteggio_totale'] ?? '0')) ?></strong>
                            </p>
                            <?php if ($lastRacePrize !== null): ?>
                                <div class="corse-prize-summary">
                                    <h6><?= $translator->translate('plugin.corse.student.prize_title') ?></h6>
                                    <div class="corse-prize-items">
                                        <span><strong><?= (int) ($lastRacePrize['xp'] ?? 0) ?></strong> XP</span>
                                        <span><strong><?= (int) ($lastRacePrize['coins'] ?? 0) ?></strong> <?= $translator->translate('plugin.corse.coins') ?></span>
                                        <span>
                                            <strong><?= (int) ($lastRacePrize['chest_qty'] ?? 0) ?></strong>
                                            <?= $translator->translate('plugin.corse.chests') ?>
                                            <?= htmlspecialchars((string) ($lastRacePrize['chest_rarity'] ?? '')) ?>
                                        </span>
                                    </div>
                                    <?php if ((int) ($lastRacePrize['reward_bonus'] ?? 0) > 0): ?>
                                        <small class="text-muted">
                                            <?= sprintf($translator->translate('plugin.corse.student.reward_bonus_applied'), (int) $lastRacePrize['reward_bonus']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <?php if (!$canClaimLastRace): ?>
                                    <p class="text-success mb-0"><?= $translator->translate('plugin.corse.student.prize_already_claimed') ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($canClaimLastRace): ?>
                                <form method="post" action="/studenti/plugin/corse/gare/<?= (int) ($lastRace['id_corsa'] ?? 0) ?>/riscuoti">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-gift me-1"></i><?= $translator->translate('plugin.corse.student.claim') ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.not_ranked') ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.hangar') ?></h6>
        </div>
        <div class="card-body">
            <div class="corse-card-grid">
                <?php foreach ($ownedCars as $car): ?>
                    <div class="corse-car-card">
                        <img src="<?= htmlspecialchars((string) ($car['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?>">
                        <div>
                            <strong><?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?></strong>
                            <?php if ((int) ($car['equipaggiata'] ?? 0) === 1): ?>
                                <span class="badge bg-success"><?= $translator->translate('plugin.corse.student.equipped_badge') ?></span>
                            <?php endif; ?>
                            <?php $renderCarAbilities($car); ?>
                        </div>
                        <?php if ((int) ($car['equipaggiata'] ?? 0) !== 1): ?>
                            <form method="post" action="/studenti/plugin/corse/auto/<?= (int) ($car['id_studente_car'] ?? 0) ?>/equipaggia">
                                <button class="btn btn-sm btn-primary" type="submit"><?= $translator->translate('plugin.corse.student.equip') ?></button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if ($ownedCars === []): ?>
                    <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.no_owned') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.shop') ?></h6>
        </div>
        <div class="card-body">
            <div class="corse-card-grid">
                <?php foreach ($shopCars as $car): ?>
                    <div class="corse-car-card">
                        <img src="<?= htmlspecialchars((string) ($car['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?>">
                        <div>
                            <strong><?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?></strong>
                            <div class="text-muted"><?= (int) ($car['costo'] ?? 0) ?> <?= $translator->translate('plugin.corse.coins') ?></div>
                            <?php $renderCarAbilities($car); ?>
                        </div>
                        <form method="post" action="/studenti/plugin/corse/auto/<?= (int) ($car['id_car'] ?? 0) ?>/acquista">
                            <button class="btn btn-sm btn-warning" type="submit"><?= $translator->translate('plugin.corse.student.buy') ?></button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php if ($shopCars === []): ?>
                    <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.student.shop_empty') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.history') ?></h6>
                </div>
                <div class="card-body">
                    <?php $rows = $history; require __DIR__ . '/student-table.php'; ?>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.student.leaderboard') ?></h6>
                </div>
                <div class="card-body">
                    <?php $rows = $leaderboard; require __DIR__ . '/leaderboard-table.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
