<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$activeMonster = is_array($activeMonster ?? null) ? $activeMonster : null;
$monsters = is_array($monsters ?? null) ? $monsters : [];
$heroes = is_array($heroes ?? null) ? $heroes : [];
$items = is_array($items ?? null) ? $items : [];
$logs = is_array($logs ?? null) ? $logs : [];
$itemTypes = is_array($itemTypes ?? null) ? $itemTypes : [];
$translateItemType = static function (string $type) use ($translator): string {
    $translated = $translator->translate('plugin.ftm.item_type.' . $type);
    return $translated !== '' ? $translated : $type;
};
?>
<div class="container-fluid ftm-page">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-100"><?= $translator->translate('plugin.ftm.teacher.title') ?></h1>
        <?php if ($activeMonster !== null): ?>
            <form method="post" action="/docenti/plugin/fight-the-monster/mostro/agisci">
                <button class="btn btn-danger" type="submit">
                    <i class="fas fa-bolt me-1"></i><?= $translator->translate('plugin.ftm.teacher.monster_act') ?>
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.new_monster') ?></h6></div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/fight-the-monster/mostri" enctype="multipart/form-data">
                        <input class="form-control mb-2" name="nome_mostro" placeholder="<?= $translator->translate('plugin.ftm.monster.name') ?>" required>
                        <input class="form-control mb-2" type="number" min="1" name="hp_massimi" placeholder="HP" required>
                        <div class="row">
                            <div class="col-md-4 mb-2"><input class="form-control" type="number" min="0" name="danno_cuori" placeholder="<?= $translator->translate('plugin.ftm.hearts') ?>"></div>
                            <div class="col-md-4 mb-2"><input class="form-control" type="number" min="0" name="danno_mana" placeholder="<?= $translator->translate('plugin.ftm.mana') ?>"></div>
                            <div class="col-md-4 mb-2"><input class="form-control" type="number" min="0" name="danno_monete" placeholder="<?= $translator->translate('plugin.ftm.coins') ?>"></div>
                        </div>
                        <input class="form-control mb-2" type="number" min="1" name="drop_count" value="1" placeholder="<?= $translator->translate('plugin.ftm.drop_count') ?>">
                        <input class="form-control mb-3" type="file" name="monster_image" accept=".png,.jpg,.jpeg,.gif,.webp" required>
                        <button class="btn btn-primary" type="submit"><?= $translator->translate('common.save') ?></button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.new_hero') ?></h6></div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/fight-the-monster/eroi">
                        <input class="form-control mb-2" name="nome_eroe" placeholder="<?= $translator->translate('plugin.ftm.hero.name') ?>" required>
                        <input class="form-control mb-3" type="number" min="0" max="100" name="bonus_set_terzo_forziere" placeholder="<?= $translator->translate('plugin.ftm.hero.set_bonus') ?>">
                        <button class="btn btn-primary" type="submit"><?= $translator->translate('common.save') ?></button>
                    </form>
                    <?php if ($heroes !== []): ?>
                        <hr>
                        <h6 class="font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.heroes') ?></h6>
                        <?php foreach ($heroes as $hero): ?>
                            <form class="ftm-manage-form" method="post" action="/docenti/plugin/fight-the-monster/eroi">
                                <input type="hidden" name="id_hero" value="<?= (int) ($hero['id_hero'] ?? 0) ?>">
                                <input class="form-control form-control-sm" name="nome_eroe" value="<?= htmlspecialchars((string) ($hero['nome_eroe'] ?? '')) ?>" required>
                                <input class="form-control form-control-sm" type="number" min="0" max="100" name="bonus_set_terzo_forziere" value="<?= (int) ($hero['bonus_set_terzo_forziere'] ?? 0) ?>">
                                <button class="btn btn-sm btn-outline-primary" type="submit"><?= $translator->translate('common.save_changes') ?></button>
                            </form>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.new_item') ?></h6></div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/fight-the-monster/armi" enctype="multipart/form-data">
                        <input class="form-control mb-2" name="nome_item" placeholder="<?= $translator->translate('plugin.ftm.item.name') ?>" required>
                        <select class="form-control mb-2" name="fk_hero" required>
                            <option value=""><?= $translator->translate('plugin.ftm.hero.select') ?></option>
                            <?php foreach ($heroes as $hero): ?>
                                <option value="<?= (int) ($hero['id_hero'] ?? 0) ?>"><?= htmlspecialchars((string) ($hero['nome_eroe'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-control mb-2" name="tipologia" required>
                            <?php foreach ($itemTypes as $type): ?>
                                <option value="<?= htmlspecialchars((string) $type) ?>"><?= htmlspecialchars($translateItemType((string) $type)) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input class="form-control mb-2" type="number" min="0" max="100" name="bonus_doppio_forziere" placeholder="<?= $translator->translate('plugin.ftm.item.double_bonus') ?>">
                        <input class="form-control mb-3" type="file" name="item_image" accept=".png,.jpg,.jpeg,.gif,.webp" required>
                        <button class="btn btn-primary" type="submit"><?= $translator->translate('common.save') ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="ftm-monster-frame mb-4">
                <?php if ($activeMonster === null): ?>
                    <p class="mb-0"><?= $translator->translate('plugin.ftm.no_active_monster') ?></p>
                <?php else: ?>
                    <?php $hpPercent = max(0, min(100, ((int) $activeMonster['hp_attuali'] / max(1, (int) $activeMonster['hp_massimi'])) * 100)); ?>
                    <img src="<?= htmlspecialchars((string) ($activeMonster['immagine'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($activeMonster['nome_mostro'] ?? '')) ?>">
                    <h2><?= htmlspecialchars((string) ($activeMonster['nome_mostro'] ?? '')) ?></h2>
                    <div class="ftm-hp"><span style="width: <?= $hpPercent ?>%"></span></div>
                    <p><?= (int) $activeMonster['hp_attuali'] ?> / <?= (int) $activeMonster['hp_massimi'] ?> HP</p>
                    <p><?= $translator->translate('plugin.ftm.evil_powers') ?>: -<?= (int) $activeMonster['danno_cuori'] ?> <?= $translator->translate('plugin.ftm.hearts') ?>, -<?= (int) $activeMonster['danno_mana'] ?> <?= $translator->translate('plugin.ftm.mana') ?>, -<?= (int) $activeMonster['danno_monete'] ?> <?= $translator->translate('plugin.ftm.coins') ?></p>
                <?php endif; ?>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.monsters') ?></h6></div>
                <div class="card-body">
                    <?php foreach ($monsters as $monster): ?>
                        <div class="ftm-list-row">
                            <img src="<?= htmlspecialchars((string) ($monster['immagine'] ?? '')) ?>" alt="">
                            <div><strong><?= htmlspecialchars((string) ($monster['nome_mostro'] ?? '')) ?></strong><br><?= (int) $monster['hp_attuali'] ?> / <?= (int) $monster['hp_massimi'] ?> HP</div>
                            <form method="post" action="/docenti/plugin/fight-the-monster/mostri/<?= (int) ($monster['id_monster'] ?? 0) ?>/attiva">
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= $translator->translate('plugin.ftm.teacher.activate') ?></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.teacher.items') ?></h6></div>
                <div class="card-body">
                    <?php foreach ($items as $item): ?>
                        <?php $itemId = (int) ($item['id_item'] ?? 0); ?>
                        <div class="ftm-list-row ftm-manage-row <?= ((int) ($item['attivo'] ?? 1) === 1) ? '' : 'ftm-inactive-row' ?>">
                            <img src="<?= htmlspecialchars((string) ($item['immagine'] ?? '')) ?>" alt="">
                            <form class="ftm-item-edit-form" method="post" action="/docenti/plugin/fight-the-monster/armi" enctype="multipart/form-data">
                                <input type="hidden" name="id_item" value="<?= $itemId ?>">
                                <input class="form-control form-control-sm" name="nome_item" value="<?= htmlspecialchars((string) ($item['nome_item'] ?? '')) ?>" required>
                                <select class="form-control form-control-sm" name="fk_hero" required>
                                    <?php foreach ($heroes as $hero): ?>
                                        <?php $selected = (int) ($hero['id_hero'] ?? 0) === (int) ($item['fk_hero'] ?? 0); ?>
                                        <option value="<?= (int) ($hero['id_hero'] ?? 0) ?>" <?= $selected ? 'selected' : '' ?>><?= htmlspecialchars((string) ($hero['nome_eroe'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control form-control-sm" name="tipologia" required>
                                    <?php foreach ($itemTypes as $type): ?>
                                        <option value="<?= htmlspecialchars((string) $type) ?>" <?= (string) ($item['tipologia'] ?? '') === (string) $type ? 'selected' : '' ?>><?= htmlspecialchars($translateItemType((string) $type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input class="form-control form-control-sm" type="number" min="0" max="100" name="bonus_doppio_forziere" value="<?= (int) ($item['bonus_doppio_forziere'] ?? 0) ?>">
                                <input class="form-control form-control-sm" type="file" name="item_image" accept=".png,.jpg,.jpeg,.gif,.webp">
                                <div class="ftm-actions">
                                    <?php if ((int) ($item['attivo'] ?? 1) !== 1): ?>
                                        <span class="badge bg-secondary"><?= $translator->translate('plugin.ftm.item.inactive') ?></span>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-primary" type="submit"><?= $translator->translate('common.save_changes') ?></button>
                                </div>
                            </form>
                            <form method="post" action="/docenti/plugin/fight-the-monster/armi/<?= $itemId ?>/elimina" onsubmit="return confirm(<?= htmlspecialchars(json_encode($translator->translate('plugin.ftm.item.delete_confirm'), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>);">
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= $translator->translate('common.delete') ?></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.ftm.logs') ?></h6></div>
                <div class="card-body">
                    <?php foreach ($logs as $log): ?><p class="mb-1"><?= htmlspecialchars((string) ($log['testo'] ?? '')) ?></p><?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
