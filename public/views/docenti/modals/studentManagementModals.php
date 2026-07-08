<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$classroom = $classroom ?? null;
$availableImportClasses = $availableImportClasses ?? [];
?>
<div class="modal fade" id="studentFormModal" tabindex="-1" role="dialog" aria-labelledby="studentFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentFormModalLabel"><?= $translator->translate('teacher.students.modal.form.title') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="studentForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <?= $translator->translate('teacher.students.modal.form.active_class') ?>:
                        <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                        <?php if (!empty($classroom['anno_scolastico'])): ?>
                            <span class="text-muted">— <?= $translator->translate('teacher.students.modal.form.school_year') ?> <?= htmlspecialchars((string) $classroom['anno_scolastico']) ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" id="studentId" name="id_studente" value="0">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="studentName" class="form-label"><?= $translator->translate('teacher.students.modal.form.name') ?></label>
                            <input type="text" class="form-control" id="studentName" name="nome_studente" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="studentSurname" class="form-label"><?= $translator->translate('teacher.students.modal.form.surname') ?></label>
                            <input type="text" class="form-control" id="studentSurname" name="cognome_studente" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="studentEmail" class="form-label"><?= $translator->translate('teacher.students.modal.form.email') ?></label>
                            <input type="email" class="form-control" id="studentEmail" name="email_studente" required>
                            <small class="form-text text-muted" id="studentEmailHelp"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="studentUsername" class="form-label"><?= $translator->translate('teacher.students.modal.form.username') ?></label>
                            <input type="text" class="form-control" id="studentUsername" name="username_preview" disabled>
                            <small class="form-text text-muted"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="studentGender" class="form-label"><?= $translator->translate('teacher.students.modal.form.gender') ?></label>
                            <select class="form-control" id="studentGender" name="sesso_studente">
                                <option value="M"><?= $translator->translate('teacher.students.modal.form.gender_male') ?></option>
                                <option value="F"><?= $translator->translate('teacher.students.modal.form.gender_female') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="studentPei" class="form-label"><?= $translator->translate('teacher.students.modal.form.pei') ?></label>
                            <select class="form-control" id="studentPei" name="studente104">
                                <option value="0"><?= $translator->translate('common.no') ?></option>
                                <option value="1"><?= $translator->translate('common.yes') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-0">
                            <label for="studentPassword" class="form-label"><?= $translator->translate('teacher.students.modal.form.password') ?></label>
                            <input type="text" class="form-control" id="studentPassword" name="password_studente" autocomplete="off">
                            <small class="form-text text-muted" id="studentPasswordHelp"><?= $translator->translate('teacher.students.modal.form.password_help') ?></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    <button type="submit" class="btn btn-primary" id="saveStudentButton"><?= $translator->translate('common.save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="csvImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="csvImportModalLabel"><?= $translator->translate('teacher.students.modal.csv.title') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="csvImportForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <?= $translator->translate('teacher.students.modal.csv.format_help') ?>
                    </div>
                    <div class="mb-3">
                        <label for="csvFileUpload" class="form-label"><?= $translator->translate('teacher.students.modal.csv.file_label') ?></label>
                        <input type="file" class="form-control" id="csvFileUpload" name="fileUpload" accept=".csv,text/csv" required>
                        <small class="form-text text-muted"><?= $translator->translate('teacher.students.modal.csv.password_help') ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    <button type="submit" class="btn btn-success" id="confirmCsvImportButton"><?= $translator->translate('teacher.students.modal.csv.submit') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="classImportModal" tabindex="-1" role="dialog" aria-labelledby="classImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="classImportModalLabel"><?= $translator->translate('teacher.students.modal.class_import.title') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="classImportForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <?= $translator->translate('teacher.students.modal.class_import.help') ?>
                    </div>
                    <div class="mb-3">
                        <label for="sourceClassId" class="form-label"><?= $translator->translate('teacher.students.modal.class_import.source_class') ?></label>
                        <select class="form-control" id="sourceClassId" name="id_classe_scelta" required>
                            <option value="0"><?= $translator->translate('common.none') ?></option>
                            <?php foreach ($availableImportClasses as $importClass): ?>
                                <option value="<?= (int) $importClass['id'] ?>">
                                    <?= htmlspecialchars($importClass['name']) ?> - <?= htmlspecialchars($importClass['school_year']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    <button type="submit" class="btn btn-primary" id="confirmClassImportButton"><?= $translator->translate('teacher.students.modal.class_import.submit') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStudentModalLabel"><?= $translator->translate('teacher.students.modal.delete.title') ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteStudentId" value="0">
                <p class="mb-0"><?= $translator->translate('teacher.students.modal.delete.question') ?> <strong id="deleteStudentName"></strong> <?= $translator->translate('teacher.students.modal.delete.question_suffix') ?></p>
                <small class="text-muted"></small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                <button type="button" class="btn btn-danger" id="confirmDeleteStudentButton"><?= $translator->translate('common.delete') ?></button>
            </div>
        </div>
    </div>
</div>
