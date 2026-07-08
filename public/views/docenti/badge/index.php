<?php

use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$subjects = $subjects ?? [];
$topics = $topics ?? [];
$badges = $badges ?? [];
$selectedSubjectId = (int) ($selectedSubjectId ?? 0);
$selectedTopicId = (int) ($selectedTopicId ?? 0);
$currentUserId = (int) ((Session::get('user')['id'] ?? 0));
$translator = new TranslationService();
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.badges.page.title') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.classes.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-primary shadow-sm" id="openImportExportBadgeModal">
                    <i class="fas fa-file-import fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.badges.import_export') ?>
                </button>
                <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateBadgeModal">
                    <i class="fas fa-trophy fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.badges.add') ?>
                </button>
            </div>
        </div>

        <div id="badge-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.badges.filters') ?></h6>
            </div>
            <div class="card-body">
                <form method="get" action="/docenti/badge" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="badgeSubjectFilter" class="form-label"><?= $translator->translate('teacher.badges.subject') ?></label>
                        <select class="form-control" id="badgeSubjectFilter" name="materia" data-selected-topic="<?= (int) $selectedTopicId ?>">
                            <option value="0"><?= $translator->translate('teacher.badges.subject.all') ?></option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= (int) $subject['id_materia'] ?>" <?= ((int) $subject['id_materia'] === $selectedSubjectId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="badgeTopicFilter" class="form-label"><?= $translator->translate('teacher.badges.topic') ?></label>
                        <select class="form-control" id="badgeTopicFilter" name="argomento">
                            <option value="0"><?= $translator->translate('teacher.badges.topic.all') ?></option>
                            <?php foreach ($topics as $topic): ?>
                                <option value="<?= (int) $topic['id_argomento'] ?>" <?= ((int) $topic['id_argomento'] === $selectedTopicId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.badges.filters.apply') ?></button>
                        <a href="/docenti/badge" class="btn btn-outline-secondary"><?= $translator->translate('teacher.badges.filters.reset') ?></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.badges.available') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="badgeTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width:14%"><?= $translator->translate('teacher.badges.name') ?></th>
                            <th style="width:18%"><?= $translator->translate('teacher.badges.subject') ?> / <?= $translator->translate('teacher.badges.topic') ?></th>
                            <th style="width:22%"><?= $translator->translate('teacher.badges.description') ?></th>
                            <th style="width:12%"><?= $translator->translate('teacher.badges.image') ?></th>
                            <th style="width:7%"><?= $translator->translate('teacher.badges.exercises') ?></th>
                            <th style="width:7%"><?= $translator->translate('teacher.badges.minimum_average.short') ?></th>
                            <th style="width:8%"><?= $translator->translate('teacher.badges.gender') ?></th>
                            <th style="width:12%"><?= $translator->translate('teacher.badges.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($badges as $badge): ?>
                            <?php $canManage = (int) ($badge['fk_utente_creatore'] ?? 0) === $currentUserId; ?>
                            <tr>
                                <td><?= htmlspecialchars((string) ($badge['nome_badge'] ?? '')) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars((string) ($badge['nome_materia'] ?? '-')) ?></strong>
                                    <div class="text-muted"><?= htmlspecialchars((string) ($badge['nome_argomento'] ?? '-')) ?></div>
                                </td>
                                <td><?= htmlspecialchars_decode((string) ($badge['descrizione'] ?? ''), ENT_QUOTES) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($badge['img_badge'])): ?>
                                        <img src="<?= htmlspecialchars((string) $badge['img_badge']) ?>" alt="Badge" style="max-width: 100px; max-height: 100px; border:1px solid #efefef; box-shadow: 1px 1px 4px rgba(0,0,0,.2);">
                                    <?php else: ?>
                                        <span class="text-muted">N/D</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= (int) ($badge['num_esercizi'] ?? 0) ?></td>
                                <td class="text-center"><?= number_format((float) ($badge['media_minima'] ?? 0), 1, ',', '') ?></td>
                                <td class="text-center"><?= htmlspecialchars((string) ($badge['sesso'] ?? 'U')) ?></td>
                                <td class="text-center">
                                    <?php if ($canManage): ?>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning js-edit-badge"
                                                data-badge-id="<?= (int) ($badge['id_badge'] ?? 0) ?>"
                                                data-badge-name="<?= htmlspecialchars((string) ($badge['nome_badge'] ?? ''), ENT_QUOTES) ?>"
                                                data-badge-description="<?= htmlspecialchars_decode((string) ($badge['descrizione'] ?? ''), ENT_QUOTES) ?>"
                                                data-badge-topic-id="<?= (int) ($badge['fk_argomento'] ?? 0) ?>"
                                                data-badge-subject-id="<?= (int) ($badge['id_materia'] ?? 0) ?>"
                                                data-badge-exercises="<?= (int) ($badge['num_esercizi'] ?? 0) ?>"
                                                data-badge-average="<?= htmlspecialchars((string) ($badge['media_minima'] ?? '0'), ENT_QUOTES) ?>"
                                                data-badge-gender="<?= htmlspecialchars((string) ($badge['sesso'] ?? 'U'), ENT_QUOTES) ?>">
                                                <?= $translator->translate('common.update') ?>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger js-delete-badge"
                                                data-badge-id="<?= (int) ($badge['id_badge'] ?? 0) ?>"
                                                data-badge-name="<?= htmlspecialchars((string) ($badge['nome_badge'] ?? ''), ENT_QUOTES) ?>">
                                                <?= $translator->translate('common.delete') ?>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted"><?= $translator->translate('teacher.badges.read_only') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="badgeFormModal" tabindex="-1" role="dialog" aria-labelledby="badgeFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="badgeForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="badgeFormModalLabel"><?= $translator->translate('teacher.badges.add') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= $translator->translate('common.close') ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="badgeId" name="id_badge" value="0">

                            <div class="form-group">
                                <label for="badgeName"><?= $translator->translate('teacher.badges.name') ?></label>
                                <input type="text" class="form-control" id="badgeName" name="nome_badge" required>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="badgeSubject"><?= $translator->translate('teacher.badges.subject') ?></label>
                                    <select class="form-control" id="badgeSubject" required>
                                        <option value=""><?= $translator->translate('teacher.badges.subject.select') ?></option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?= (int) $subject['id_materia'] ?>"><?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="badgeTopic"><?= $translator->translate('teacher.badges.topic') ?></label>
                                    <select class="form-control" id="badgeTopic" name="id_argomento" required>
                                        <option value=""><?= $translator->translate('teacher.badges.subject.select_first') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="badgeDescription"><?= $translator->translate('teacher.badges.description') ?></label>
                                <textarea class="form-control" id="badgeDescription" name="descrizione_badge" rows="3" required></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="badgeExercises"><?= $translator->translate('teacher.badges.exercises.minimum') ?></label>
                                    <input type="number" min="1" class="form-control" id="badgeExercises" name="num_esercizi" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="badgeAverage"><?= $translator->translate('teacher.badges.minimum_average') ?></label>
                                    <input type="number" min="0" max="10" step="0.1" class="form-control" id="badgeAverage" name="media_minima" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="badgeGender"><?= $translator->translate('teacher.badges.gender') ?></label>
                                    <select class="form-control" id="badgeGender" name="sesso">
                                        <option value="U"><?= $translator->translate('teacher.badges.gender.universal') ?></option>
                                        <option value="M"><?= $translator->translate('teacher.badges.gender.male') ?></option>
                                        <option value="F"><?= $translator->translate('teacher.badges.gender.female') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="badgeImage"><?= $translator->translate('teacher.badges.image') ?> badge</label>
                                <input type="file" class="form-control-file" id="badgeImage" name="badge_image" accept=".png,.jpg,.jpeg,.gif,.webp">
                                <small class="text-muted"><?= $translator->translate('teacher.badges.image.keep_current_hint') ?></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.cancel') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveBadgeButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="badgeImportExportModal" tabindex="-1" role="dialog" aria-labelledby="badgeImportExportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="badgeImportExportModalLabel"><?= $translator->translate('teacher.badges.import_export') ?> badge</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="<?= $translator->translate('common.close') ?>">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><?= $translator->translate('teacher.badges.export.by_subject') ?></h6>
                                <form method="GET" action="/docenti/badge/export/0" id="exportBadgeForm">
                                    <select class="form-control mb-2" id="exportBadgeSubjectSelect" required>
                                        <option value=""><?= $translator->translate('teacher.badges.subject.select') ?></option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?= (int) $subject['id_materia'] ?>"><?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary btn-sm" type="submit"><?= $translator->translate('teacher.badges.export.zip') ?></button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h6><?= $translator->translate('teacher.badges.import.from_file') ?></h6>
                                <form method="POST" action="/docenti/badge/import-file" enctype="multipart/form-data" id="importBadgeForm">
                                    <input type="file" class="form-control mb-2" name="badge_archive" accept=".zip" required>
                                    <button class="btn btn-success btn-sm" type="submit"><?= $translator->translate('teacher.badges.import.zip') ?></button>
                                </form>
                                <small class="text-muted d-block mt-2"><?= $translator->translate('teacher.badges.import.hint') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
