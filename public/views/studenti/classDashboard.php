<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$availableCharacters = $availableCharacters ?? [];
$hero = $hero ?? null;
$team = $team ?? null;
$teammates = $teammates ?? [];
?>
<div class="container-fluid class-dashboard-page">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) $classroom['colore']) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) $classroom['icona']) ?>"></i>
                </div>
                <h1 class="class-title">
                    <?= $translator->translate('student.class_dashboard.class') ?> <?= htmlspecialchars((string) $classroom['nome_classe']) ?> <?= htmlspecialchars((string) $classroom['anno_scolastico']) ?>
                </h1>
            </div>
        </div>
    <?php endif; ?>

    <?php if (is_array($student) && (int) ($student['fk_personaggio'] ?? 0) === 0): ?>
        <div class="character-selection-intro mb-5">
            <h2 class="selection-title"><?= $translator->translate('student.class_dashboard.character.none_selected') ?></h2>
            <p class="selection-subtitle"><?= $translator->translate('student.class_dashboard.character.choose_hint.before') ?> <strong><?= $translator->translate('student.class_dashboard.character.choose_hint.name') ?></strong> <?= $translator->translate('student.class_dashboard.character.choose_hint.or') ?> <strong><?= $translator->translate('student.class_dashboard.character.choose_hint.portrait') ?></strong>.</p>
        </div>

        <div class="row choose-character-selection-grid">
            <?php foreach ($availableCharacters as $character): ?>
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="choose-character-card">
                        <form method="post" action="/studenti/classe/personaggio" class="m-0">
                            <input type="hidden" name="character_id" value="<?= (int) $character['id_personaggio'] ?>">
                            <button type="submit" class="choose-character-select w-100 border-0 p-0 text-start bg-transparent">
                                <div class="choose-character-header">
                                    <h3><?= htmlspecialchars((string) $character['nome_personaggio']) ?></h3>
                                    <span class="select-hint"><?= $translator->translate('student.class_dashboard.character.select_hint') ?></span>
                                </div>

                                <div class="choose-character-body">
                                    <div class="choose-character-avatar">
                                        <img src="<?= htmlspecialchars('/' . ltrim(preg_replace('#^(\./|\.\./)+#', '', (string) $character['immagine']), '/')) ?>"
                                             alt="<?= htmlspecialchars((string) $character['nome_personaggio']) ?>"
                                             style="border-color:<?= htmlspecialchars((string) $character['bordercolor']) ?>; box-shadow:0 0 12px <?= htmlspecialchars((string) $character['color']) ?>;">
                                    </div>

                                    <div class="choose-character-description">
                                        <?= htmlspecialchars_decode(html_entity_decode((string) $character['descrizione'])) ?>
                                    </div>
                                </div>

                                <div class="choose-character-stats">
                                    <div class="choose-stat life">
                                        <i class="fas fa-heart"></i>
                                        <span><?= (int) $character['vita_iniziale'] ?> <?= $translator->translate('student.class_dashboard.stat.lives') ?></span>
                                    </div>
                                    <div class="choose-stat mana">
                                        <i class="fas fa-yin-yang"></i>
                                        <span><?= (int) $character['mana_iniziale'] ?> <?= $translator->translate('student.class_dashboard.stat.mana') ?></span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (is_array($hero)): ?>
        <div class="character-hero"<?php if (!empty($hero['backgroundImage'])): ?> style="background: url('<?= htmlspecialchars($hero['backgroundImage']) ?>') center/cover no-repeat;"<?php endif; ?>>
            <div class="character-overlay">
                <div class="character-top">
                    <div class="character-avatar-box small-avatar" style="<?= htmlspecialchars((string) ($hero['avatar']['style'] ?? '')) ?>">
                        <?php if (($hero['avatar']['mode'] ?? 'single') === 'layered'): ?>
                            <div class="avatar-layered">
                                <?php if (!empty($hero['avatar']['backgroundSrc'])): ?>
                                    <img src="<?= htmlspecialchars((string) $hero['avatar']['backgroundSrc']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.character_background') ?>">
                                <?php endif; ?>
                                <?php if (!empty($hero['avatar']['baseSrc'])): ?>
                                    <img src="<?= htmlspecialchars((string) $hero['avatar']['baseSrc']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.base_character') ?>">
                                <?php endif; ?>
                                <?php if (!empty($hero['avatar']['hairSrc'])): ?>
                                    <img src="<?= htmlspecialchars((string) $hero['avatar']['hairSrc']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.character_hair') ?>">
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <img class="avatar-img" src="<?= htmlspecialchars((string) ($hero['avatar']['src'] ?? '')) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.character_avatar') ?>">
                        <?php endif; ?>
                    </div>

                    <div class="character-header">
                        <div class="character-name"><?= htmlspecialchars((string) $hero['displayName']) ?></div>
                        <div class="player-name"><?= htmlspecialchars((string) $hero['playerName']) ?></div>
                        <div class="player-meta mt-3">
                            <span><i class="fas fa-coins"></i> <?= (int) $hero['coins'] ?> <?= $translator->translate('student.class_dashboard.stat.coins') ?></span>
                            <?php if (is_array($student)): ?>
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars((string) $student['username']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="character-hud compact">
                        <div class="hud-box life <?= !empty($hero['life']['danger']) ? 'danger' : '' ?>">
                            <div class="hud-icons">
                                <?php for ($i = 1; $i <= (int) $hero['life']['maximum']; $i++): ?>
                                    <i class="fas fa-heart <?= (int) $hero['life']['current'] >= $i ? 'full' : 'empty' ?>"></i>
                                <?php endfor; ?>
                                <?php if (!empty($hero['shield']['enabled'])): ?>
                                    <?php for ($i = 1; $i <= (int) $hero['shield']['maximum']; $i++): ?>
                                        <i class="fas fa-shield <?= (int) $hero['shield']['current'] >= $i ? 'full-shield' : 'empty text-secondary' ?>"></i>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="hud-box mana">
                            <div class="hud-icons">
                                <?php for ($i = 1; $i <= (int) $hero['mana']['maximum']; $i++): ?>
                                    <i class="fas fa-yin-yang <?= (int) $hero['mana']['current'] >= $i ? 'full' : 'empty' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="hud-box level">
                            <div class="level-number"><?= (int) $hero['level'] ?></div>
                            <div class="xp-bar">
                                <div class="xp-fill" style="width:<?= (int) $hero['xpPercent'] ?>%"></div>
                            </div>
                            <div class="xp-text"><?= htmlspecialchars((string) $hero['xpLabel']) ?></div>
                        </div>
                    </div>
                </div>

                <div class="layout">
                    <aside class="sx">
                        <div class="description-column">
                            <h3><?= $translator->translate('student.class_dashboard.character.description') ?></h3>
                            <p><?= $hero['descriptionHtml'] ?></p>
                        </div>
                    </aside>

                    <section class="r1">
                        <div class="party-member main">
                            <img src="<?= htmlspecialchars((string) $hero['mainCharacterImage']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.main_character') ?>">
                            <span class="member-label"><?= $translator->translate('student.class_dashboard.member.you') ?></span>
                            <?php if (!empty($hero['petImage'])): ?>
                                <div class="pet-label"><img src="<?= htmlspecialchars((string) $hero['petImage']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.pet') ?>"></div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="r2">
                        <div style="width:100%; text-align:center; padding-top:2vh;">
                            <?php if (is_array($team)): ?>
                                <div class="team-badge-box floating-team" style="width:100%">
                                    <div class="team-emblem">
                                        <?php if (!empty($team['emblem'])): ?>
                                            <img src="<?= htmlspecialchars((string) $team['emblem']) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.class_dashboard.alt.team_emblem'), (string) $team['name'])) ?>">
                                        <?php else: ?>
                                            <div class="emblem-placeholder">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="team-content">
                                        <div class="team-name"><?= htmlspecialchars((string) $team['name']) ?> <?= $translator->translate('student.class_dashboard.team.label') ?></div>
                                        <div class="team-power-wrapper"<?php if (!empty($team['powerTooltip'])): ?> title="<?= htmlspecialchars((string) $team['powerTooltip'], ENT_QUOTES) ?>"<?php endif; ?>>
                                            <?php if (!empty($team['powerEnabled'])): ?>
                                                <form method="post" action="/studenti/classe/potere-squadra" class="m-0">
                                                    <button type="submit" class="team-power-button">
                                                        <i class="fas fa-bolt"></i>
                                                        <span><?= htmlspecialchars((string) $team['powerLabel']) ?></span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button type="button" class="team-power-button disabled" disabled>
                                                    <i class="fas fa-bolt"></i>
                                                    <span><?= htmlspecialchars((string) $team['powerLabel']) ?></span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="team-power-description"><?= htmlspecialchars((string) $team['powerDescription']) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="r3">
                        <?php foreach ($teammates as $teammate): ?>
                            <div class="party-member cell">
                                <img src="<?= htmlspecialchars((string) $teammate['image']) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.class_dashboard.alt.teammate'), (string) $teammate['fullName'])) ?>">
                                <span class="member-label-comp"><?= htmlspecialchars((string) $teammate['fullName']) ?></span>
                                <?php if (!empty($teammate['petImage'])): ?>
                                    <div class="pet-label-member"><img src="<?= htmlspecialchars((string) $teammate['petImage']) ?>" alt="<?= $translator->translate('student.class_dashboard.alt.teammate_pet') ?>"></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
