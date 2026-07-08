<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$subjects = is_array($subjects ?? null) ? $subjects : [];
$topics = is_array($topics ?? null) ? $topics : [];
$selectedSubjectId = (int) ($selectedSubjectId ?? 0);
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-2" style="color:#FFD700;"><?= $translator->translate('testcreator.import_questions.menu.title') ?></h3>
            <small class="text-white"><?= $translator->translate('testcreator.import_questions.menu.subtitle') ?></small>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.import_questions.menu.card_title') ?></h6>
                <div class="d-flex align-items-center gap-2">
                    <label for="importSubjectFilter" class="mb-0 mr-2"><?= $translator->translate('testcreator.topics.subject_filter') ?></label>
                    <select id="importSubjectFilter" class="form-control" style="min-width: 240px;">
                        <option value="0"><?= $translator->translate('testcreator.topics.select_subject') ?></option>
                        <?php foreach ($subjects as $subject): ?>
                            <?php $subjectId = (int) ($subject['id_materia'] ?? 0); ?>
                            <option value="<?= $subjectId ?>" <?= $subjectId === $selectedSubjectId ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) ($subject['nome_materia'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="btn-group mb-3" role="group" aria-label="<?= htmlspecialchars($translator->translate('testcreator.import_questions.menu.import_type')) ?>">
                    <button type="button" class="btn btn-success" disabled><?= $translator->translate('testcreator.import_questions.menu.from_teachers') ?></button>
                    <a href="/testcreator/import-domande/csv" class="btn btn-outline-success"><?= $translator->translate('testcreator.import_questions.menu.from_csv') ?></a>
                </div>

                <div class="alert alert-info mb-3" id="selectedImportMessage"><?= $translator->translate('testcreator.import_questions.menu.active_teachers') ?></div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorImportTopicsTable" width="100%" cellspacing="0" data-initial-subject-id="<?= $selectedSubjectId ?>">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.topics.subject_id') ?></th>
                            <th><?= $translator->translate('testcreator.topics.topic') ?></th>
                            <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            <th style="width: 230px;"><?= $translator->translate('testcreator.print_templates.action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topics as $topic): ?>
                            <?php $topicId = (int) ($topic['id_argomento'] ?? 0); ?>
                            <tr>
                                <td><?= (int) ($topic['fk_materia'] ?? 0) ?></td>
                                <td><?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?></td>
                                <td><?= htmlspecialchars((string) ($topic['nome_materia'] ?? '')) ?></td>
                                <td>
                                    <a
                                        href="/testcreator/import-domande/argomenti/<?= $topicId ?>/altri-docenti"
                                        class="btn btn-primary btn-sm js-import-go"
                                        data-topic-id="<?= $topicId ?>"
                                        data-teachers-url="/testcreator/import-domande/argomenti/<?= $topicId ?>/altri-docenti">
                                        <?= $translator->translate('testcreator.teacher_emails.import') ?>
                                    </a>
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
