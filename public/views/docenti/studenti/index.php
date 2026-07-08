<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$students = $students ?? [];
$availableImportClasses = $availableImportClasses ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('dash.studenticlasse') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('dash.anno') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-success shadow-sm" id="openCsvImportModal">
                    <i class="fas fa-file-import fa-sm me-1"></i>
                    <?= $translator->translate('teacher.students.modal.csv.title') ?>
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" id="openClassImportModal">
                    <i class="fas fa-users fa-sm me-1"></i>
                    <?= $translator->translate('teacher.students.modal.class_import.title') ?>
                </button>
                <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateStudentModal">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $translator->translate('teacher.students.modal.form.title') ?>
                </button>
            </div>
        </div>

        <div id="student-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center gap-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('students') ?></h6>
                <small class="text-muted"></small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="studentTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:15%"><?= $translator->translate('register.surname') ?></th>
                                <th style="width:15%"><?= $translator->translate('register.name') ?></th>
                                <th style="width:20%">Email</th>
                                <th style="width:14%">Username</th>
                                <th style="width:10%"><?= $translator->translate('teacher.students.modal.form.gender') ?></th>
                                <th style="width:10%"><?= $translator->translate('pei') ?></th>
                                <th style="width:8%"><?= $translator->translate('common.update') ?></th>
                                <th style="width:8%"><?= $translator->translate('common.delete') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr class="<?= $student['l104'] ? 'table-info' : '' ?>">
                                    <td><?= htmlspecialchars($student['surname']) ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                    <td><?= htmlspecialchars($student['username']) ?></td>
                                    <td><?= htmlspecialchars($student['gender']) ?></td>
                                    <td>
                                        <span class="badge <?= $student['l104'] ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                            <?= $student['l104'] ? 'Yes' : 'No' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning js-edit-student"
                                            data-student-id="<?= (int) $student['id'] ?>"
                                            data-student-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                            data-student-surname="<?= htmlspecialchars($student['surname'], ENT_QUOTES) ?>"
                                            data-student-email="<?= htmlspecialchars($student['email'], ENT_QUOTES) ?>"
                                            data-student-username="<?= htmlspecialchars($student['username'], ENT_QUOTES) ?>"
                                            data-student-gender="<?= htmlspecialchars($student['gender'], ENT_QUOTES) ?>"
                                            data-student-l104="<?= $student['l104'] ? '1' : '0' ?>">
                                            <i class="fas fa-pen fa-sm text-white-50 me-1"></i>
                                            <?= $translator->translate('common.update') ?>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger js-delete-student"
                                            data-student-id="<?= (int) $student['id'] ?>"
                                            data-student-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                            data-student-surname="<?= htmlspecialchars($student['surname'], ENT_QUOTES) ?>">
                                            <i class="fas fa-trash fa-sm text-white-50 me-1"></i>
                                            <?= $translator->translate('common.delete') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
