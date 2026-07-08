<?php

use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$flashes = Flash::all();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classes = $classes ?? [];
$selectedClassId = $selectedClassId ?? null;
$classesByYear = [];
foreach ($classes as $class) {
    $year = (string) ($class['anno_scolastico'] ?? '');
    if (!isset($classesByYear[$year])) {
        $classesByYear[$year] = [];
    }
    $classesByYear[$year][] = $class;
}
?>
<div class="container-fluid">
    <?php foreach ($flashes as $flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($translator->translate($flash['message'])) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endforeach; ?>

    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="page-title mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1><?= $translator->translate('teacher.classes.title') ?></h1>
                <p><?= $translator->translate('teacher.classes.subtitle') ?></p>
            </div>
            <button class="btn btn-success" data-toggle="modal" data-target="#teacherClassCreateModal">
                <i class="fas fa-plus-circle mr-2"></i><?= $translator->translate('teacher.classes.new') ?>
            </button>
        </div>

        <?php if ($classes === []): ?>
            <div class="alert alert-info">
                <?= $translator->translate('teacher.classes.empty') ?>
            </div>
        <?php else: ?>
            <?php foreach ($classesByYear as $year => $yearClasses): ?>
                <div class="class-year-section">
                    <h2 class="class-year-title"><?= $translator->translate('teacher.classes.year')." ".htmlspecialchars($year) ?></h2>
                    <div class="row class-grid">
                        <?php foreach ($yearClasses as $class): ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="class-card" style="--accent: <?= htmlspecialchars((string) $class['colore']) ?>;">
                                    <div class="class-card-header position-relative">
                                        <?php if ((int) $selectedClassId === (int) $class['id_classe']): ?>
                                            <span class="badge badge-success position-absolute" style="top: 1rem; right: 1rem;">
                                                <?= $translator->translate('teacher.classes.current') ?>
                                            </span>
                                        <?php endif; ?>
                                        <i class="fas <?= htmlspecialchars((string) $class['icona']) ?>"></i>
                                    </div>

                                    <div class="class-card-body">
                                        <span class="class-year"><?= htmlspecialchars((string) $class['anno_scolastico']) ?></span>
                                        <h3 class="class-name"><?= htmlspecialchars((string) $class['nome_classe']) ?></h3>
                                    </div>

                                    <div class="class-card-footer d-flex gap-2">
                                        <a href="/docenti/classi/<?= (int) $class['id_classe'] ?>/seleziona" class="btn btn-sm btn-primary">
                                            <i class="fas fa-arrow-right mr-1"></i><?= $translator->translate('teacher.classes.enter') ?>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary js-edit-class"
                                            data-toggle="modal"
                                            data-target="#teacherClassEditModal"
                                            data-id="<?= (int) $class['id_classe'] ?>"
                                            data-name="<?= htmlspecialchars((string) $class['nome_classe'], ENT_QUOTES) ?>"
                                            data-icon="<?= htmlspecialchars((string) $class['icona'], ENT_QUOTES) ?>"
                                            data-color="<?= htmlspecialchars((string) $class['colore'], ENT_QUOTES) ?>">
                                            <i class="fas fa-edit mr-1"></i><?= $translator->translate('common.update') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NOT_TEACHER): ?>
        <div class="alert alert-danger"><?= $translator->translate('permission.noteacher') ?></div>
    <?php else: ?>
        <div class="alert alert-danger">
            <?= $translator->translate('permission.nologin') ?> <a href="/">LOGIN</a>
        </div>
    <?php endif; ?>
</div>
