<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$student = $student ?? null;
$powers = $powers ?? [];
$allies = $allies ?? [];

$currentMana = (int) ($student['mana'] ?? 0);
$maxMana = max(1, (int) ($student['mana_massimo'] ?? 1));
$ratio = $maxMana > 0 ? $currentMana / $maxMana : 0;
$hue = 190 + (30 * $ratio);
$light1 = 75 - (25 * $ratio);
$light2 = 60 - (25 * $ratio);
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

    <div class="card shadow mb-4">
        <div class="mana-slots">
            <i class="fas fa-flask mana-flask"></i>
            <?php for ($i = 1; $i <= $maxMana; $i++): ?>
                <?php if ($i <= $currentMana): ?>
                    <div class="slot full <?= $currentMana <= 3 ? 'broken' : '' ?>"
                         style="--hue: <?= htmlspecialchars((string) $hue) ?>; --light1: <?= htmlspecialchars((string) $light1) ?>%; --light2: <?= htmlspecialchars((string) $light2) ?>%;">
                    </div>
                <?php else: ?>
                    <div class="slot"></div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <div class="card-body">
            <?php if ($powers === []): ?>
                <div class="alert alert-info mb-0"><?= $translator->translate('student.powers.none_assigned') ?></div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($powers as $power): ?>
                        <?php
                        $powerId = (int) ($power['id_potere'] ?? 0);
                        $manaCost = (int) ($power['mana_necessario'] ?? 0);
                        $requiresTarget = !empty($power['requires_target']);
                        ?>
                        <div class="col">
                            <div class="power-card text-center h-100">
                                <img src="<?= htmlspecialchars((string) ($power['img_src'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($power['nome_potere'] ?? $translator->translate('student.powers.power'))) ?>" class="power-img">
                                <div class="power-name">
                                    <?php
                                    if($_SESSION['lang']=='it' or $power['nome_potere_en']==NULL)
                                        echo htmlspecialchars((string) ($power['nome_potere'] ?? $translator->translate('student.powers.power'))); 
                                    else if($_SESSION['lang']=='en')
                                        echo htmlspecialchars((string) ($power['nome_potere_en'] ?? $translator->translate('student.powers.power')));
                                    ?>
                                </div>
                                <div class="mana-cost"><i class="fas fa-yin-yang"></i> <?= $manaCost ?> MANA</div>
                                <div class="power-desc">
                                    <?php
                                    if($_SESSION['lang']=='it' or $power['descrizione_potere_en']==NULL)
                                        echo htmlspecialchars_decode(html_entity_decode((string) ($power['descrizione_potere'] ?? '')));
                                    else if($_SESSION['lang']=='en')
                                        echo htmlspecialchars_decode(html_entity_decode((string) ($power['descrizione_potere_en'] ?? '')));
                                    ?>
                                </div>

                                <?php if ($currentMana >= $manaCost): ?>
                                    <form method="post" action="/studenti/poteri/use" class="d-grid gap-2" onsubmit="return confirm(window.cqT('student.powers.confirm.use', <?= htmlspecialchars(json_encode($translator->translate('student.powers.confirm.use'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>));">
                                        <input type="hidden" name="power_id" value="<?= $powerId ?>">

                                        <?php if ($requiresTarget): ?>
                                            <select class="form-select" name="target_student_id" required>
                                                <option value=""><?= $translator->translate('student.powers.choose_classmate') ?></option>
                                                <?php foreach ($allies as $ally): ?>
                                                    <?php
                                                    $allyId = (int) ($ally['id_studente'] ?? 0);
                                                    if ($powerId === 7 && $allyId === (int) ($student['id_studente'] ?? 0)) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <option value="<?= $allyId ?>">
                                                        <?= htmlspecialchars(trim((string) ($ally['nome'] ?? '') . ' ' . (string) ($ally['cognome'] ?? ''))) ?>
                                                        - <?= $translator->translate('student.powers.lives') ?>: <?= (int) ($ally['vite'] ?? 0) ?>
                                                        - <?= $translator->translate('student.powers.mana') ?>: <?= (int) ($ally['mana'] ?? 0) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>

                                        <button type="submit" class="use-btn"><?= $translator->translate('student.powers.use_power') ?></button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-danger mb-0"><?= $translator->translate('student.powers.not_enough_mana') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
