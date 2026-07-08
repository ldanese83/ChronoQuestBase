<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$translator = new TranslationService();
$quest = $quest ?? null;
$chapter = $chapter ?? null;
$exercise = $exercise ?? null;
$exerciseState = $exerciseState ?? 'new';
$delivery = $delivery ?? null;
$materialiSupporto = $materialiSupporto ?? [];
$quizQuestions = $quizQuestions ?? [];
$isEvaluated = $exerciseState === 'evaluated';
$submitUrl = '/studenti/quest/' . (int) ($quest['id_quest'] ?? 0) . '/capitoli/' . (int) ($chapter['id_capitolo'] ?? 0) . '/esercizi/' . (int) ($exercise['id_esercizio'] ?? 0) . '/consegna';
$deleteFileUrl = '/studenti/quest/' . (int) ($quest['id_quest'] ?? 0) . '/capitoli/' . (int) ($chapter['id_capitolo'] ?? 0) . '/esercizi/' . (int) ($exercise['id_esercizio'] ?? 0) . '/consegna/elimina-file';
$forziereVinto = (string) ($_GET['forziere_vinto'] ?? '');
$forziereParts = explode('+', $forziereVinto, 2);
$forziereBase = (string) ($forziereParts[0] ?? '');
$forzieriExtra = isset($forziereParts[1]) ? max(0, (int) $forziereParts[1]) : 0;
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
$immaginiForzieri = [
    'comune' => '/assets/images/Forzieri/forziere_comune.png',
    'non comune' => '/assets/images/Forzieri/forziere_non_comune.png',
    'raro' => '/assets/images/Forzieri/forziere_raro.png',
    'epico' => '/assets/images/Forzieri/forziere_epico.png',
    'leggendario' => '/assets/images/Forzieri/forziere_leggendario.png',
];
$imgForziere = $forziereBase !== '' ? ($immaginiForzieri[$forziereBase] ?? '') : '';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null && $chapter !== null && $exercise !== null): ?>
        <?php
        $translatedExerciseType = trim((string) ($exercise['tipo_en'] ?? ''));
        $displayExerciseType = $useEnglishDbTranslations && $translatedExerciseType !== ''
            ? $translatedExerciseType
            : (string) ($exercise['tipo'] ?? '');
        ?>
        <?php if ($forziereVinto !== '' && $imgForziere !== ''): ?>
            <div class="modal fade" id="forziereVintoModal" tabindex="-1" aria-labelledby="forziereVintoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="forziereVintoModalLabel"><?= $translator->translate('student.quest.exercise.chest_won.title') ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('student.quest.exercise.close')) ?>"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p><?= htmlspecialchars(sprintf($translator->translate('student.quest.exercise.chest_won.message'), $forziereBase)) ?></p>
                            <?php if ($forzieriExtra > 0): ?>
                                <p><?= htmlspecialchars(sprintf($translator->translate('plugin.ftm.student.extra_chests_won'), $forzieriExtra)) ?></p>
                            <?php endif; ?>
                            <img class="img-fluid" style="max-height:220px" src="<?= htmlspecialchars($imgForziere) ?>" alt="<?= htmlspecialchars(sprintf($translator->translate('student.quest.exercise.alt.chest'), $forziereBase)) ?>">
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary" href="/studenti/forzieri"><?= $translator->translate('student.quest.exercise.chest_won.go_to_chests') ?></a>
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><?= $translator->translate('student.quest.exercise.close') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.bootstrap && window.bootstrap.Modal) {
                        var modalElement = document.getElementById('forziereVintoModal');
                        if (modalElement) {
                            window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <div class="quest-header mb-4 d-flex justify-content-between align-items-center">
            <div class="quest-header-left">
                <h1><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></h1>
                <span class="quest-subtitle"><?= htmlspecialchars($displayExerciseType) ?> &middot; <?= htmlspecialchars((string) ($exercise['nome_argomento'] ?? '')) ?></span>
            </div>
            <a href="/studenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitoli/<?= (int) ($chapter['id_capitolo'] ?? 0) ?>" class="quest-back-btn"><i class="fas fa-arrow-left"></i> <?= $translator->translate('student.quest.exercise.back_to_quest') ?></a>
        </div>

        <form action="<?= htmlspecialchars($submitUrl) ?>" method="POST" id="form_capitolo" enctype="multipart/form-data">
            <div class="row reward-bar mb-5">
                <div class="col-md-6">
                    <div class="reward-card xp"><div class="icon"><i class="fas fa-bolt"></i></div><div class="reward-value">+<?= (int) ($exercise['xp_display'] ?? 0) ?></div><div class="reward-label">XP</div></div>
                </div>
                <div class="col-md-6">
                    <div class="reward-card coins"><div class="icon"><i class="fas fa-coins"></i></div><div class="reward-value">+<?= (int) ($exercise['money_display'] ?? 0) ?></div><div class="reward-label"><?= $translator->translate('student.quest.exercise.coins') ?></div></div>
                </div>
            </div>

            <div class="quest-box mb-4">
                <div class="quest-box-title"><i class="fas fa-book-open"></i> <?= $translator->translate('student.quest.exercise.support_material') ?></div>
                <div class="quest-box-content">
                    <?php if (count($materialiSupporto) === 0): ?>
                        <span class="muted"><?= $translator->translate('student.quest.exercise.no_materials') ?></span>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($materialiSupporto as $mat): ?>
                                <li><a href="<?= htmlspecialchars((string) ($mat['url'] ?? '#')) ?>" target="_blank"><?= htmlspecialchars((string) ($mat['nome'] ?? $translator->translate('student.quest.exercise.material'))) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="quest-box story-box mb-4">
                <div class="quest-box-title"><i class="fas fa-feather-alt"></i> <?= $translator->translate('student.quest.exercise.chapter_story') ?></div>
                <div id="story" class="quest-box-content story-content"><?= html_entity_decode((string) ($exercise['storia_esercizio'] ?? '')) ?></div>
            </div>

            <?php if ((string) ($exercise['tipo'] ?? '') === 'Domanda aperta'): ?>
                <div class="quest-box warning mb-4"><i class="fas fa-scroll"></i> <?= html_entity_decode((string) ($exercise['testo_esercizio'] ?? '')) ?></div>
                <div class="mb-4">
                    <label class="quest-label"><i class="fas fa-feather-alt"></i> <?= $translator->translate('student.quest.exercise.your_answer') ?></label>
                    <textarea name="testo_risposta" id="testo_risposta" rows="10" class="form-control" <?= $isEvaluated ? 'readonly' : '' ?>><?= htmlspecialchars(html_entity_decode((string) ($delivery['testo_risposta'] ?? ''))) ?></textarea>
                </div>
            <?php elseif ((string) ($exercise['tipo'] ?? '') === 'Esercizio da consegnare'): ?>
                <div class="quest-box primary mb-4"><div class="quest-box-title"><i class="fas fa-scroll"></i><strong><?= $translator->translate('student.quest.exercise.exercise_text') ?></strong></div><div class="quest-box-content"><?= html_entity_decode((string) ($exercise['testo_esercizio'] ?? '')) ?></div></div>
                <?php if (!empty($delivery['file_consegnato'])): ?>
                    <div class="quest-box info mb-4"><strong><?= $translator->translate('student.quest.exercise.delivered_files') ?></strong><br>
                        <?php $fileDir = dirname(__DIR__, 4) . '/public' . (string) $delivery['file_consegnato']; ?>
                        <?php if (is_dir($fileDir)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?= $translator->translate('student.quest.exercise.file_name') ?></th>
                                            <th class="text-center"><?= $translator->translate('student.quest.exercise.actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php foreach (array_diff(scandir($fileDir) ?: [], ['.', '..']) as $f): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= htmlspecialchars((string) $delivery['file_consegnato'] . $f) ?>" target="_blank">
                                                <?= htmlspecialchars((string) $f) ?>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!$isEvaluated) { ?>
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                name="file_name"
                                                value="<?= htmlspecialchars((string) $f) ?>"
                                                formaction="<?= htmlspecialchars($deleteFileUrl) ?>"
                                                formmethod="POST"
                                                formnovalidate
                                                onclick="return confirm(window.cqT('student.quest.exercise.file_delete_confirm', <?= htmlspecialchars(json_encode($translator->translate('student.quest.exercise.file_delete_confirm'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>));"
                                                title="<?= htmlspecialchars($translator->translate('student.quest.exercise.delete_file')) ?>"
                                            >
                                                <i class="fas fa-trash" style="margin-left:8px"></i>
                                            </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!$isEvaluated): ?>
                    <div class="quest-box upload mb-4">
                        <strong><?= $exerciseState === 'submitted' ? $translator->translate('student.quest.exercise.edit_delivery') : $translator->translate('student.quest.exercise.upload_files') ?></strong>
                        <div id="dropzoneArea" class="mt-3 js-exercise-dropzone dropzone" data-url="<?= htmlspecialchars($submitUrl) ?>"></div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <input type="hidden" id="num_crocette" value="<?= count(array_filter($quizQuestions, static fn ($q) => (int) ($q['fk_tipo_domanda'] ?? 0) === 2)) ?>">
                <?php foreach ($quizQuestions as $q): ?>
                    <div class="quiz-domanda mb-4">
                        <div class="alert alert-warning quiz-testo"><?= html_entity_decode((string) ($q['domanda'] ?? '')) ?></div>
                        <?php if ((int) ($q['fk_tipo_domanda'] ?? 0) === 2): ?>
                            <input type="hidden" id="rispdom_<?= (int) ($q['id_domanda'] ?? 0) ?>" name="rispdom_<?= (int) ($q['id_domanda'] ?? 0) ?>" value="<?= (int) ($q['student_answer']['fk_risposta'] ?? 0) ?>">
                            <?php foreach (($q['answers'] ?? []) as $answer): ?>
                                <?php $selected = (int) ($q['student_answer']['fk_risposta'] ?? 0) === (int) ($answer['id_risposta'] ?? 0); ?>
                                <?php
                                    $isCorrectAnswer = (int) ($answer['corretta'] ?? 0) === 1;
                                    $answerStateClass = $selected ? 'selected' : '';
                                    if ($isEvaluated) {
                                        if ($isCorrectAnswer) {
                                            $answerStateClass .= ' quiz-risposta-corretta';
                                        } elseif ($selected) {
                                            $answerStateClass .= ' quiz-risposta-sbagliata';
                                        }
                                    }
                                    $answerClickableClass = !$isEvaluated ? 'quiz-risposta-clickable' : '';
                                ?>
                                <div class="input-group mb-2 quiz-risposta <?= trim($answerStateClass . ' ' . $answerClickableClass) ?>" <?= !$isEvaluated ? 'onclick="seleziona_risp(' . (int) ($q['id_domanda'] ?? 0) . ',' . (int) ($answer['id_risposta'] ?? 0) . ')"' : '' ?> >
                                    <span class="input-group-text"><i id="icona_risp<?= (int) ($answer['id_risposta'] ?? 0) ?>" class="fas <?= $selected ? 'fa-check' : 'fa-angle-right' ?>"></i></span>
                                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars(html_entity_decode((string) ($answer['risposta'] ?? '')), ENT_QUOTES) ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <textarea class="form-control" rows="6" name="testo_risposta<?= (int) ($q['id_domanda'] ?? 0) ?>" <?= $isEvaluated ? 'readonly' : '' ?>><?= htmlspecialchars(html_entity_decode((string) ($q['student_answer']['testo_risposta'] ?? ''))) ?></textarea>
                            <?php if ($isEvaluated): ?>
                                <?php $commentoDocente = trim((string) ($q['student_answer']['commento_prof'] ?? '')); ?>
                                <?php if ($commentoDocente !== ''): ?>
                                    <div class="quest-box info mt-3 mb-0 quiz-commento-docente">
                                        <div class="quest-box-title"><i class="fas fa-comment-dots"></i> <?= $translator->translate('student.quest.exercise.teacher_comment') ?></div>
                                        <div class="quest-box-content"><?= nl2br($commentoDocente) ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!$isEvaluated): ?>
                <div class="row_form footer-action">
                    <button id="salva_risp" class="btn <?= $exerciseState === 'new' ? 'btn-success' : 'btn-warning' ?> btn-icon-split btn-lg"><span style="font-size:1.5rem;margin-top:2%"><i class="fas fa-save"></i></span> <span class="text"><?= (string) ($exercise['tipo'] ?? '') === 'Esercizio da consegnare' && $exerciseState === 'submitted' ? $translator->translate('student.quest.exercise.save_new_delivery') : $translator->translate('student.quest.exercise.save_answer') ?></span></button>
                </div>
            <?php else: ?>
                <div class="quest-box voto <?= ((int) ($delivery['valutazione'] ?? 0) < 5) ? 'negativo' : (((int) ($delivery['valutazione'] ?? 0) <= 6) ? 'medio' : 'positivo') ?>">
                    <i class="fas fa-award"></i>
                    <div class="voto-numero"><?= (int) ($delivery['valutazione'] ?? 0) ?></div>
                    <div class="voto-label"><?= $translator->translate('student.quest.exercise.final_evaluation') ?></div>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
