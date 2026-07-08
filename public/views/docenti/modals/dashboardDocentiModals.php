<?php

use App\Service\TranslationService;

$translator = new TranslationService();
?>
<!-- Ricompensa Modal -->
<div class="modal fade" id="ricompensaModal" tabindex="-1" role="dialog" aria-labelledby="ricompensaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ricompensaModalLabel"><?= $translator->translate('dash.give_ricompensa') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="<?= $translator->translate('common.close') ?>">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ricompensaStudentIds" value="">
                <div class="form-group">
                    <label for="ricompensaXp"><?= $translator->translate('dash.xp') ?></label>
                    <input type="number" min="0" class="form-control" id="ricompensaXp" placeholder="0">
                </div>
                <div class="form-group mt-2">
                    <label for="ricompensaMonete"><?= $translator->translate('dash.coins') ?></label>
                    <input type="number" min="0" class="form-control" id="ricompensaMonete" placeholder="0">
                </div>
                <div class="form-group mt-2">
                    <label for="ricompensaMotivazione"><?= $translator->translate('dash.why') ?></label>
                    <textarea class="form-control" id="ricompensaMotivazione" rows="3" placeholder="<?= $translator->translate('dash.why') ?>"></textarea>
                </div>
                <p class="text-muted mt-2 mb-0"><?= $translator->translate('dash.what') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="salvaRicompensa"><?= $translator->translate('dash.give') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Assegna punizione Modal -->
<div class="modal fade" id="punizioneModal" tabindex="-1" role="dialog" aria-labelledby="punizioneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="punizioneModalLabel">
                    <?= $translator->translate('teacher.dashboard.death_punishment.modal.title') ?>
                    <span id="punizioneStudenteNome"></span>
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="punizioneStudenteId" value="0">
                <p class="mb-3"><?= $translator->translate('teacher.dashboard.death_punishment.modal.intro') ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="modalita_punizione" id="modalitaRandom" value="random" checked>
                    <label class="form-check-label" for="modalitaRandom"><?= $translator->translate('teacher.dashboard.death_punishment.mode.random') ?></label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="modalita_punizione" id="modalitaExisting" value="existing">
                    <label class="form-check-label" for="modalitaExisting"><?= $translator->translate('teacher.dashboard.death_punishment.mode.existing') ?></label>
                </div>
                <div id="existingPunizioneWrapper" class="mt-2 d-none">
                    <label for="existingPunizioneSelect"><?= $translator->translate('teacher.dashboard.death_punishment.field.punishment') ?></label>
                    <select class="form-control" id="existingPunizioneSelect"></select>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="modalita_punizione" id="modalitaNew" value="new">
                    <label class="form-check-label" for="modalitaNew"><?= $translator->translate('teacher.dashboard.death_punishment.mode.new') ?></label>
                </div>
                <div id="nuovaPunizioneWrapper" class="mt-2 d-none">
                    <div class="form-group">
                        <label for="nuovaDescrizionePunizione"><?= $translator->translate('teacher.dashboard.death_punishment.field.description') ?></label>
                        <textarea class="form-control" id="nuovaDescrizionePunizione" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nuoviGiorniConsegna"><?= $translator->translate('teacher.dashboard.death_punishment.field.days') ?></label>
                        <input type="number" min="1" class="form-control" id="nuoviGiorniConsegna">
                    </div>
                    <div class="form-group">
                        <label for="nuovaImgPunizione"><?= $translator->translate('teacher.dashboard.death_punishment.field.image_optional') ?></label>
                        <input type="file" class="form-control" id="nuovaImgPunizione">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                <button type="button" class="btn btn-primary" id="salvaPunizioneMorte"><?= $translator->translate('teacher.dashboard.death_punishment.save') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Togli cuore Modal -->
<div class="modal fade" id="togliCuoreModal" tabindex="-1" role="dialog" aria-labelledby="togliCuoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="togliCuoreModalLabel">
                    <?= $translator->translate('less.heart') ?>
                    <span id="nome_tc"></span>
                    <span id="cognome_tc"></span>
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 pt-2">
                        <label for="motivazione"><?= $translator->translate('dash.why') ?></label>
                    </div>
                    <div class="col-md-12 pt-2">
                        <input style="width:100%" type="text" id="motivazione" class="form-control">
                        <input type="hidden" id="id_studente" value="0">
                        <input type="hidden" id="bulkHeartStudentIds" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="salvaCuore">Save</button>
            </div>
        </div>
    </div>
</div>
