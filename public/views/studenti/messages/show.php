<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$message = $message ?? null;
$conversation = $conversation ?? [];
?>
<div class="container-fluid py-4">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && is_array($message)): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-envelope-open-text me-2"></i>
                    <?= htmlspecialchars((string) ($message['subject'] ?? '')) ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2 text-muted small">
                    <strong><?= $translator->translate('messages.from') ?>:</strong> <?= htmlspecialchars((string) ($message['teacher_name'] ?? '')) ?><br>
                    <strong><?= $translator->translate('messages.email') ?>:</strong> <?= htmlspecialchars((string) ($message['teacher_email'] ?? '')) ?><br>
                    <strong><?= $translator->translate('messages.send') ?>:</strong> <?= htmlspecialchars((string) ($message['date'] ?? '')) ?>
                </div>

                <hr>

                <div class="mb-3">
                    <?= (string) ($message['body'] ?? '') ?>
                </div>

                <div class="text-end">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRispostaMessaggio">
                        <i class="fas fa-reply me-1"></i> <?= $translator->translate('messages.answer') ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-comments me-2"></i>
                    <?= $translator->translate('messages.history') ?>
                </h6>
            </div>
            <div class="card-body">
                <?php if ($conversation !== []): ?>
                    <div class="list-group">
                        <?php foreach ($conversation as $entry): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong><?= htmlspecialchars($entry['sender_name']) ?></strong>
                                    <small class="text-muted"><?= htmlspecialchars($entry['date']) ?></small>
                                </div>
                                <div class="mt-2">
                                    <?= $entry['body'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <em class="text-muted"><?= $translator->translate('messages.noprevious') ?></em>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal fade" id="modalRispostaMessaggio" tabindex="-1" aria-labelledby="modalRispostaMessaggioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRispostaMessaggioLabel">
                            <i class="fas fa-reply me-2"></i> <?= $translator->translate('messages.answer_m') ?>
                        </h5>
                        <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="studentReplyMessageForm" method="post" action="/studenti/messages/<?= (int) $message['id'] ?>/reply">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="replySubject" class="form-label"><?= $translator->translate('messages.object_reply') ?></label>
                                <input type="text" id="replySubject" name="oggettoRisposta" class="form-control" value="<?= htmlspecialchars((string) ($message['subject'] ?? '')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="replyBody" class="form-label"><?= $translator->translate('messages.text_reply') ?></label>
                                <textarea id="replyBody" name="testoRisposta" class="form-control" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $translator->translate('teacher.quest.import_export.cancel') ?></button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> <?= $translator->translate('send.mail') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
