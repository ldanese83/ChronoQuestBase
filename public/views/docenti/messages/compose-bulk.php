<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$selectedStudents = $selectedStudents ?? [];
$selectedStudentIds = $selectedStudentIds ?? [];
$classroom = $classroom ?? null;
?>
<div class="container-fluid py-4">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('messages.new_message') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
            </h1>
            <a href="/docenti/dashboard" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> <?= $translator->translate('messages.back') ?>
            </a>
        </div>

        <div class="card mb-4 shadow-sm">
            <form method="post" action="/docenti/messages/new-bulk">
                <div class="card-body">
                    <input type="hidden" name="ids" value="<?= htmlspecialchars(implode(',', $selectedStudentIds)) ?>">

                    <div class="mb-3">
                        <div class="alert alert-info mb-0">
                            <strong><?= $translator->translate('messages.to') ?></strong>
                            <?php if ($selectedStudents !== []): ?>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($selectedStudents as $student): ?>
                                        <li>
                                            <?= htmlspecialchars((string) ($student['name'] ?? '')) ?>
                                            <?php if (!empty($student['email'])): ?>
                                                (<?= htmlspecialchars((string) $student['email']) ?>)
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="ms-1"><?= $translator->translate('js.students.none_selected') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="composeSubject" class="form-label"><?= $translator->translate('messages.object_m') ?></label>
                        <input type="text" id="composeSubject" name="oggettoRisposta" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="composeBody" class="form-label"><?= $translator->translate('messages.text_m') ?></label>
                        <textarea id="composeBody" name="testoRisposta" class="form-control" rows="8"></textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="/docenti/dashboard" class="btn btn-secondary"><?= $translator->translate('teacher.quest.import_export.cancel') ?></a>
                    <button type="submit" class="btn btn-primary" <?= $selectedStudents === [] ? 'disabled' : '' ?>>
                        <i class="fas fa-paper-plane me-1"></i> <?= $translator->translate('send.mail') ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
