<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$teachers = $teachers ?? [];
$classroom = $classroom ?? null;
?>
<div class="container-fluid py-4">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('student.messages.new_for_class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
            </h1>
            <a href="/studenti/messages" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> <?= $translator->translate('student.messages.back_to_messages') ?>
            </a>
        </div>

        <div class="card mb-4 shadow-sm">
            <form id="studentComposeMessageForm" method="post" action="/studenti/messages">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="profRisposta" class="form-label"><?= $translator->translate('student.messages.send_to') ?></label>
                        <select id="profRisposta" name="profRisposta" class="form-control" required>
                            <option value=""><?= $translator->translate('student.messages.select_teacher') ?></option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= (int) $teacher['id'] ?>"><?= htmlspecialchars((string) $teacher['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="composeSubject" class="form-label"><?= $translator->translate('student.messages.subject') ?></label>
                        <input type="text" id="composeSubject" name="oggettoRisposta" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="composeBody" class="form-label"><?= $translator->translate('student.messages.body') ?></label>
                        <textarea id="composeBody" name="testoRisposta" class="form-control" rows="8"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> <?= $translator->translate('send.mail') ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
