<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$classmates = $classmates ?? [];
$rivalTeams = $rivalTeams ?? [];
?>
<div class="container-fluid classmates-page">
    <?php if (is_array($classroom)): ?>
        <div class="class-header mb-4" style="background: linear-gradient(135deg, <?= htmlspecialchars((string) $classroom['colore']) ?>, #00acc1);">
            <div class="d-flex align-items-center gap-3">
                <div class="class-icon">
                    <i class="fa-solid <?= htmlspecialchars((string) $classroom['icona']) ?>"></i>
                </div>
                <h1 class="class-title">
                    <?= $translator->translate('student.classmates.class') ?> <?= htmlspecialchars((string) $classroom['nome_classe']) ?> <?= htmlspecialchars((string) $classroom['anno_scolastico']) ?>
                </h1>
            </div>
        </div>
    <?php endif; ?>

    <div class="classmates-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="classmates-icon">
                <i class="fa-solid fa-people-group"></i>
            </div>
            <h2 class="classmates-title"><?= $translator->translate('student.classmates.title') ?></h2>
        </div>
    </div>

    <ul class="nav nav-tabs classmates-tabs mb-4" id="classmatesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="classmates-tab" data-bs-toggle="tab" href="#classmates-pane" role="tab" aria-controls="classmates-pane" aria-selected="true">
                <?= $translator->translate('student.classmates.tab.classmates') ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="rival-teams-tab" data-bs-toggle="tab" href="#rival-teams-pane" role="tab" aria-controls="rival-teams-pane" aria-selected="false">
                <?= $translator->translate('student.classmates.tab.rival_teams') ?>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="classmatesTabsContent">
        <div class="tab-pane fade show active" id="classmates-pane" role="tabpanel" aria-labelledby="classmates-tab">
            <div class="row">
                <?php if ($classmates === []): ?>
                    <div class="col-12">
                        <div class="empty-rival-teams"><?= $translator->translate('student.classmates.empty.classmates') ?></div>
                    </div>
                <?php else: ?>
                    <?php foreach ($classmates as $mate): ?>
                        <div class="col-xl-6 col-md-6 mb-4 mt-3">
                            <div class="ally-card">
                                <div class="ally-header">
                                    <div class="ally-name"><?= htmlspecialchars((string) $mate['fullName']) ?></div>
                                    <div class="ally-character"><?= htmlspecialchars((string) $mate['displayCharacterName']) ?></div>
                                </div>

                                <div class="ally-body">
                                    <?php if (($mate['avatar']['mode'] ?? 'single') === 'layered'): ?>
                                        <div class="ally-avatar_sovra">
                                            <div class="avatar-composite" style="<?= htmlspecialchars((string) ($mate['avatar']['style'] ?? '')) ?>">
                                                <?php if (!empty($mate['avatar']['backgroundSrc'])): ?>
                                                    <img class="layer sfondo" src="<?= htmlspecialchars((string) $mate['avatar']['backgroundSrc']) ?>" alt="<?= htmlspecialchars($translator->translate('student.classmates.alt.avatar_background')) ?>">
                                                <?php endif; ?>
                                                <?php if (!empty($mate['avatar']['baseSrc'])): ?>
                                                    <img class="layer personaggio" src="<?= htmlspecialchars((string) $mate['avatar']['baseSrc']) ?>" alt="<?= htmlspecialchars($translator->translate('student.classmates.alt.avatar_character')) ?>">
                                                <?php endif; ?>
                                                <?php if (!empty($mate['avatar']['hairSrc'])): ?>
                                                    <img class="layer capelli" src="<?= htmlspecialchars((string) $mate['avatar']['hairSrc']) ?>" alt="<?= htmlspecialchars($translator->translate('student.classmates.alt.avatar_hair')) ?>">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="ally-avatar">
                                            <img src="<?= htmlspecialchars((string) ($mate['avatar']['src'] ?? '')) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.classmates.alt.avatar'), (string) $mate['fullName'])) ?>" style="<?= htmlspecialchars((string) ($mate['avatar']['style'] ?? '')) ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="ally-stats">
                                        <div class="stat level">
                                            <i class="fas fa-trophy"></i>
                                            <?= $translator->translate('student.classmates.stat.level') ?> <strong><?= (int) $mate['level'] ?></strong>
                                        </div>
                                        <div class="stat life">
                                            <i class="fas fa-heart"></i>
                                            <?= $translator->translate('student.classmates.stat.lives') ?> <strong><?= (int) $mate['life'] ?></strong>
                                        </div>
                                        <div class="stat mana">
                                            <i class="fas fa-yin-yang"></i>
                                            <?= $translator->translate('student.classmates.stat.mana') ?> <strong><?= (int) $mate['mana'] ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="rival-teams-pane" role="tabpanel" aria-labelledby="rival-teams-tab">
            <?php if ($rivalTeams === []): ?>
                <div class="empty-rival-teams"><?= $translator->translate('student.classmates.empty.rival_teams') ?></div>
            <?php else: ?>
                <?php foreach ($rivalTeams as $team): ?>
                    <div class="enemy-team-card mb-5">
                        <div class="enemy-team-header">
                            <div class="team-badge-box enemy-team-badge">
                                <div class="team-emblem">
                                    <?php if (!empty($team['emblem'])): ?>
                                        <img src="<?= htmlspecialchars((string) $team['emblem']) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.classmates.alt.team_emblem'), (string) $team['name'])) ?>">
                                    <?php else: ?>
                                        <div class="emblem-placeholder">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="team-name" style="color:#a55308;"><?= htmlspecialchars((string) $team['name']) ?> <?= $translator->translate('student.classmates.team_suffix') ?></div>
                            </div>
                        </div>

                        <div class="enemy-team-grid">
                            <?php foreach (($team['members'] ?? []) as $member): ?>
                                <div class="enemy-member">
                                    <?php if (!empty($member['image'])): ?>
                                        <img src="<?= htmlspecialchars((string) $member['image']) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.classmates.alt.classmate'), (string) $member['fullName'])) ?>">
                                    <?php else: ?>
                                        <div class="enemy-member-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="enemy-member-name"><?= htmlspecialchars((string) $member['fullName']) ?></span>
                                    <?php if (!empty($member['petImage'])): ?>
                                        <div class="enemy-member-pet"><img src="<?= htmlspecialchars((string) $member['petImage']) ?>" alt="<?= htmlspecialchars($translator->translate('student.classmates.alt.pet')) ?>"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
