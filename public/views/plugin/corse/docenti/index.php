<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = is_array($classroom ?? null) ? $classroom : [];
$cars = is_array($cars ?? null) ? $cars : [];
$races = is_array($races ?? null) ? $races : [];
$activeRace = is_array($activeRace ?? null) ? $activeRace : null;
$activeStandings = is_array($activeStandings ?? null) ? $activeStandings : [];
$leaderboard = is_array($leaderboard ?? null) ? $leaderboard : [];
$rarities = is_array($rarities ?? null) ? $rarities : [];
?>
<div class="container-fluid corse-page">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800">
            <?= $translator->translate('plugin.corse.teacher.title') ?>
            <small class="text-muted">
                <?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?>
                <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
            </small>
        </h1>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.car_form') ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/corse/auto" enctype="multipart/form-data">
                        <input type="hidden" name="id_car" value="0">
                        <div class="mb-3">
                            <label class="form-label"><?= $translator->translate('plugin.corse.car.name') ?></label>
                            <input class="form-control" name="nome_auto" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $translator->translate('plugin.corse.car.cost') ?></label>
                                <input class="form-control" type="number" min="0" name="costo" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $translator->translate('plugin.corse.car.reward_bonus') ?></label>
                                <input class="form-control" type="number" min="0" name="bonus_ricompense" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $translator->translate('plugin.corse.car.delivery_bonus') ?></label>
                                <input class="form-control" type="number" min="0" step="0.1" name="bonus_consegne" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= $translator->translate('plugin.corse.car.grade_bonus') ?></label>
                                <input class="form-control" type="number" min="0" step="0.1" name="bonus_valutazioni" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= $translator->translate('plugin.corse.car.image') ?></label>
                            <input class="form-control" type="file" name="car_image" accept=".png,.jpg,.jpeg,.gif,.webp" required>
                        </div>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save me-1"></i><?= $translator->translate('common.save') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.cars') ?></h6>
                </div>
                <div class="card-body">
                    <div class="corse-card-grid">
                        <?php foreach ($cars as $car): ?>
                            <div class="corse-car-card">
                                <?php if (!empty($car['immagine'])): ?>
                                    <img src="<?= htmlspecialchars((string) $car['immagine']) ?>" alt="<?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?>">
                                <?php endif; ?>
                                <div>
                                    <strong><?= htmlspecialchars((string) ($car['nome_auto'] ?? '')) ?></strong>
                                    <div class="text-muted"><?= (int) ($car['costo'] ?? 0) ?> <?= $translator->translate('plugin.corse.coins') ?></div>
                                    <small>
                                        +<?= htmlspecialchars((string) ($car['bonus_consegne'] ?? '0')) ?> <?= $translator->translate('plugin.corse.deliveries_short') ?>,
                                        +<?= htmlspecialchars((string) ($car['bonus_valutazioni'] ?? '0')) ?> <?= $translator->translate('plugin.corse.grades_short') ?>
                                    </small>
                                </div>
                                <form method="post" action="/docenti/plugin/corse/auto/<?= (int) ($car['id_car'] ?? 0) ?>/elimina">
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><?= $translator->translate('common.delete') ?></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($cars === []): ?>
                            <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.teacher.no_cars') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.new_race') ?></h6>
                </div>
                <div class="card-body">
                    <?php if ($activeRace !== null): ?>
                        <div class="alert alert-info"><?= $translator->translate('plugin.corse.teacher.active_race_exists') ?></div>
                    <?php else: ?>
                        <form method="post" action="/docenti/plugin/corse/gare">
                            <div class="mb-3">
                                <label class="form-label"><?= $translator->translate('plugin.corse.race.name') ?></label>
                                <input class="form-control" name="nome_corsa" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= $translator->translate('plugin.corse.race.start') ?></label>
                                    <input class="form-control" type="datetime-local" name="data_inizio" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= $translator->translate('plugin.corse.race.end') ?></label>
                                    <input class="form-control" type="datetime-local" name="data_fine" required>
                                </div>
                            </div>

                            <?php for ($pos = 1; $pos <= 3; $pos++): ?>
                                <div class="corse-prize-row">
                                    <strong><?= $pos ?>&deg;</strong>
                                    <input class="form-control" type="number" min="0" name="premio<?= $pos ?>_xp" placeholder="XP">
                                    <input class="form-control" type="number" min="0" name="premio<?= $pos ?>_monete" placeholder="<?= $translator->translate('plugin.corse.coins') ?>">
                                    <input class="form-control" type="number" min="0" name="premio<?= $pos ?>_forzieri_qta" placeholder="<?= $translator->translate('plugin.corse.chests') ?>">
                                    <select class="form-control" name="premio<?= $pos ?>_forzieri_rarita">
                                        <?php foreach ($rarities as $rarity): ?>
                                            <option value="<?= htmlspecialchars((string) $rarity) ?>"><?= htmlspecialchars((string) $rarity) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endfor; ?>

                            <button class="btn btn-success mt-3" type="submit">
                                <i class="fas fa-flag-checkered me-1"></i><?= $translator->translate('plugin.corse.race.start_button') ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.current_standings') ?></h6>
                    <?php if ($activeRace !== null): ?>
                        <form method="post" action="/docenti/plugin/corse/gare/<?= (int) ($activeRace['id_corsa'] ?? 0) ?>/chiudi">
                            <button class="btn btn-sm btn-warning" type="submit"><?= $translator->translate('plugin.corse.race.close_button') ?></button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($activeRace === null): ?>
                        <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.teacher.no_active_race') ?></p>
                    <?php else: ?>
                        <h5><?= htmlspecialchars((string) ($activeRace['nome_corsa'] ?? '')) ?></h5>
                        <?php require __DIR__ . '/standings-table.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.general_leaderboard') ?></h6>
        </div>
        <div class="card-body">
            <?php $rows = $leaderboard; require __DIR__ . '/../studenti/leaderboard-table.php'; ?>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.corse.teacher.race_history') ?></h6>
        </div>
        <div class="card-body">
            <?php foreach ($races as $race): ?>
                <h5 class="mt-3"><?= htmlspecialchars((string) ($race['nome_corsa'] ?? '')) ?> <span class="badge bg-secondary"><?= htmlspecialchars((string) ($race['stato'] ?? '')) ?></span></h5>
                <?php $activeStandings = is_array($race['results'] ?? null) ? $race['results'] : []; ?>
                <?php require __DIR__ . '/results-table.php'; ?>
            <?php endforeach; ?>
            <?php if ($races === []): ?>
                <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.teacher.no_races') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
