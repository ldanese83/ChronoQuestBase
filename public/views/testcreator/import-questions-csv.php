<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$topic = is_array($topic ?? null) ? $topic : null;
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.import_questions.csv.title') ?></h3>
            <small class="text-white">
                <?php if ($topic !== null): ?>
                    <?= $translator->translate('testcreator.import_questions.csv.destination_topic') ?>: <?= htmlspecialchars((string) ($topic['nome_argomento'] ?? '')) ?>
                <?php else: ?>
                    <?= $translator->translate('testcreator.import_questions.csv.auto_topic_note') ?>
                <?php endif; ?>
            </small>
        </div>

        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="/testcreator/import-domande" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <?php $formAction = $topic !== null ? '/testcreator/import-domande/argomenti/' . (int) ($topic['id_argomento'] ?? 0) . '/csv' : '/testcreator/import-domande/csv'; ?>
                <form method="POST" action="<?= htmlspecialchars($formAction) ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileUpload"><?= $translator->translate('testcreator.import_questions.csv.select_file') ?></label>
                        <input type="file" class="form-control-file" name="fileUpload" id="fileUpload" accept=".csv" required>
                        <small class="form-text text-muted"><?= $translator->translate('testcreator.import_questions.csv.format_note') ?></small>
                    </div>
                    <button type="submit" class="btn btn-success"><?= $translator->translate('testcreator.import_questions.csv.upload') ?></button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
