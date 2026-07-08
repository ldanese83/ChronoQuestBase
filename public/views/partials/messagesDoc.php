<?php

use App\Service\TeacherCommunicationsService;
use App\Service\TranslationService;

$translator = new TranslationService();
$topbarData = $teacherTopbarData ?? (new TeacherCommunicationsService())->getTopbarData();
if (!($topbarData['enabled'] ?? false)) {
    return;
}

$messages = $topbarData['messages'] ?? [];
$messagesCount = (int) ($topbarData['messagesCount'] ?? 0);
?>
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope fa-fw"></i>
        <span class="badge badge-danger badge-counter"><?= $messagesCount > 0 ? $messagesCount : '' ?></span>
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header"><?= $translator->translate('messages.dropdown.title') ?></h6>

        <?php if ($messages === []): ?>
            <div class="dropdown-item text-center small text-gray-500"><?= $translator->translate('messages.dropdown.none_unread') ?></div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <a class="dropdown-item d-flex align-items-center" href="/docenti/messages/<?= (int) $message['id'] ?>">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="<?= htmlspecialchars($message['avatar']) ?>" alt="<?= htmlspecialchars($translator->translate('messages.dropdown.sender_profile')) ?>">
                        <div class="status-indicator bg-secondary"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate"><?= htmlspecialchars($message['subject']) ?></div>
                        <div class="small text-gray-500">
                            <?= $translator->translate('messages.dropdown.from') ?>: <?= htmlspecialchars($message['senderName']) ?> - <?= htmlspecialchars($message['relativeTime']) ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <a class="dropdown-item text-center small text-gray-500" href="/docenti/messages"><?= $translator->translate('messages.dropdown.show_all') ?></a>
    </div>
</li>
