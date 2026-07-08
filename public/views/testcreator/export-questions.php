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
            <h3 class="mb-2" style="color:#FFD700;"><?= $translator->translate('testcreator.export_questions.title') ?></h3>
            <small class="text-white"><?= $translator->translate('testcreator.export_questions.subtitle') ?></small>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.export_questions.card_title') ?></h6>
                <div class="d-flex align-items-center gap-2">
                    <label for="exportSubjectFilter" class="mb-0 mr-2"><?= $translator->translate('testcreator.topics.subject_filter') ?></label>
                    <select id="exportSubjectFilter" class="form-control" style="min-width: 240px;">
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
                <form method="POST" action="/testcreator/esporta-domande/csv" id="exportQuestionsForm">
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllTopicsBtn"><?= $translator->translate('testcreator.export_questions.select_all_filtered') ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearTopicsBtn"><?= $translator->translate('testcreator.export_questions.clear_all') ?></button>
                        <button type="submit" class="btn btn-success btn-sm"><?= $translator->translate('testcreator.quizzes.generate.export_csv') ?></button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="testCreatorExportTopicsTable" width="100%" cellspacing="0" data-initial-subject-id="<?= $selectedSubjectId ?>">
                            <thead>
                            <tr>
                                <th><?= $translator->translate('testcreator.topics.subject_id') ?></th>
                                <th style="width: 70px;"><?= $translator->translate('testcreator.export_questions.selected_short') ?></th>
                                <th><?= $translator->translate('testcreator.topics.topic') ?></th>
                                <th><?= $translator->translate('testcreator.index.subject') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($topics as $topic): ?>
                                <?php $topicId = (int) ($topic['id_argomento'] ?? 0); ?>
                                <tr>
                                    <td><?= (int) ($topic['fk_materia'] ?? 0) ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" class="js-topic-check" name="topic_ids[]" value="<?= $topicId ?>">
                                    </td>
                                    <td><?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($topic['nome_materia'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
