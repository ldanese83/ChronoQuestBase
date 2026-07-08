<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$classTeachers = $classTeachers ?? [];
$availableTeachers = $availableTeachers ?? [];
$translator = new TranslationService();
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.management.class_teachers_title') ?>:
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.management.school_year_label') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.management.already_associated') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="classTeachersTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:25%"><?= $translator->translate('teacher.management.surname') ?></th>
                                <th style="width:25%"><?= $translator->translate('teacher.management.name') ?></th>
                                <th style="width:25%"><?= $translator->translate('teacher.management.email') ?></th>
                                <th style="width:25%"><?= $translator->translate('teacher.management.username') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classTeachers as $teacher): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($teacher['cognome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['nome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['email'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['username'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.management.available_for_add') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="availableTeachersTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:20%"><?= $translator->translate('teacher.management.surname') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.management.name') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.management.email') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.management.username') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.management.add_to_class') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($availableTeachers as $teacher): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($teacher['cognome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['nome'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['email'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($teacher['username'] ?? '')) ?></td>
                                    <td class="text-center">
                                        <form method="post" action="/docenti/docenti/add" class="d-inline js-add-teacher-form">
                                            <input type="hidden" name="id_docente" value="<?= (int) ($teacher['id_utente'] ?? 0) ?>">
                                            <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                                <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                                                <?= $translator->translate('teacher.management.add') ?>
                                            </button>
                                        </form>
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
