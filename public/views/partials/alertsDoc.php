<?php

use App\Service\TeacherCommunicationsService;
use App\Service\TranslationService;

$translator = new TranslationService();
$topbarData = $teacherTopbarData ?? (new TeacherCommunicationsService())->getTopbarData();
if (!($topbarData['enabled'] ?? false)) {
    return;
}

$alerts = $topbarData['alerts'] ?? [];
$alertsCount = (int) ($topbarData['alertsCount'] ?? 0);
?>
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <span class="badge badge-danger badge-counter">
            <?= $alertsCount > 0 ? $alertsCount : '' ?>
        </span>
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header"><?= $translator->translate('alerts.dropdown.title') ?></h6>
        <button type="button" class="dropdown-item text-center small text-gray-500 border-0 bg-white" id="markAllAlertsReadButton">
            <?= $translator->translate('alerts.dropdown.mark_all_read') ?>
        </button>

        <?php if ($alerts === []): ?>
            <div class="dropdown-item text-center small text-gray-500"><?= $translator->translate('alerts.dropdown.none_unread') ?></div>
        <?php else: ?>
            <?php foreach ($alerts as $alert): ?>
                <a class="dropdown-item d-flex align-items-center" href="/docenti/alerts/<?= (int) $alert['id'] ?>/open" data-alert-open-url="/docenti/alerts/<?= (int) $alert['id'] ?>/open">
                    <div class="mr-3">
                        <div class="icon-circle <?= htmlspecialchars($alert['iconBgClass']) ?>">
                            <i class="fas <?= htmlspecialchars($alert['iconClass']) ?> text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500"><?= htmlspecialchars($alert['dateLabel']) ?></div>
                        <span class="font-weight-bold"><?= htmlspecialchars($alert['text']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <a class="dropdown-item text-center small text-gray-500" href="/docenti/alerts"><?= $translator->translate('alerts.dropdown.show_all') ?></a>
    </div>
</li>
