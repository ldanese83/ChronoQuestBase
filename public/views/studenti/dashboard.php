<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classes = $classes ?? [];
$selectedClassId = $selectedClassId ?? null;
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="page-title mb-4">
            <h1><?= htmlspecialchars($translator->translate('student.dashboard.title')) ?></h1>
            <p><?= htmlspecialchars($translator->translate('student.dashboard.subtitle')) ?></p>
        </div>

        <?php if ($classes === []): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($translator->translate('student.classes.empty')) ?>
            </div>
        <?php else: ?>
            <div class="row class-grid">
                <?php foreach ($classes as $class): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <a href="/studenti/classi/<?= (int) $class['id_classe'] ?>/seleziona" class="class-card-link">
                            <div class="class-card" style="--accent: <?= htmlspecialchars((string) $class['colore']) ?>;">
                                <div class="class-card-header position-relative">
                                    <?php if ((int) $selectedClassId === (int) $class['id_classe']): ?>
                                        <span class="badge badge-success position-absolute" style="top: 1rem; right: 1rem;">
                                            <?= htmlspecialchars($translator->translate('student.classes.current')) ?>
                                        </span>
                                    <?php endif; ?>
                                    <i class="fas <?= htmlspecialchars((string) $class['icona']) ?>"></i>
                                </div>

                                <div class="class-card-body">
                                    <span class="class-year"><?= htmlspecialchars((string) $class['anno_scolastico']) ?></span>
                                    <h3 class="class-name"><?= htmlspecialchars((string) $class['nome_classe']) ?></h3>
                                </div>

                                <div class="class-card-footer">
                                    <span><?= htmlspecialchars($translator->translate('student.classes.enter')) ?></span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NOT_STUDENT): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($translator->translate('permission.nostudent')) ?></div>
    <?php else: ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($translator->translate('permission.nologin')) ?> <a href="/loginStud">LOGIN</a>
        </div>
    <?php endif; ?>
</div>
