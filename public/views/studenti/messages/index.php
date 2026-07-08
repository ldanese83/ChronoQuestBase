<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$messages = $messages ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('messages.all') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('student.messages.school_year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <a href="/studenti/messages/new" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-paper-plane me-1"></i> <?= $translator->translate('student.communications.message.new') ?>
            </a>
        </div>

        <div id="messages-page-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('messages.dropdown.show_all') ?></h6>
                <div id="azioniSelezioneMessaggi" class="d-none align-items-center">
                    <span class="me-2" style="margin-right:10px">
                        <?= $translator->translate('selected') ?> <strong id="numSelezionatiMessaggi">0</strong>
                    </span>
                    <button id="btnEliminaMessaggiSelezionati" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash me-1"></i> <?= $translator->translate('common.delete') ?>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered display" id="messagesTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width:45%"><?= $translator->translate('messages.object') ?></th>
                                <th style="width:18%"><?= $translator->translate('messages.date') ?></th>
                                <th style="width:18%"><?= $translator->translate('messages.from') ?></th>
                                <th style="width:9%"><?= $translator->translate('messages.status') ?></th>
                                <th style="width:10%"><?= $translator->translate('messages.read') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?= (int) $message['id'] ?></td>
                                    <td><?= htmlspecialchars($message['subject']) ?></td>
                                    <td><?= htmlspecialchars($message['dateLabel']) ?></td>
                                    <td><?= htmlspecialchars($message['senderName']) ?></td>
                                    <td>
                                        <span class="badge <?= $message['isRead'] ? 'bg-secondary' : 'bg-success' ?>">
                                            <?= $message['isRead'] ? $translator->translate('messages.status.read') : $translator->translate('messages.status.unread') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/studenti/messages/<?= (int) $message['id'] ?>" class="btn btn-sm btn-primary shadow-sm"><?= $translator->translate('messages.read') ?></a>
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
