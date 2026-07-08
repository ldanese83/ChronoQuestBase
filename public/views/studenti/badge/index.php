<?php
use App\Service\TranslationService;

$translator = new TranslationService();
$badges = is_array($badges ?? null) ? $badges : [];
?>
<div class="container-fluid">
    <div class="badges-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="badges-icon" aria-hidden="true">
                <i class="fas fa-medal"></i>
            </div>
            <h2 class="badges-title"><?= $translator->translate('student.badges.title') ?></h2>
        </div>
    </div>

    <?php if ($badges === []): ?>
        <div class="alert alert-info mb-4"><?= $translator->translate('student.badges.empty') ?></div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($badges as $badge): ?>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="badge-card-green text-center">
                        <div class="badge-name-green"><?= htmlspecialchars((string) ($badge['nome_badge'] ?? '')) ?></div>

                        <img
                            class="badge-image-green"
                            src="<?= htmlspecialchars((string) ($badge['img_badge'] ?? '')) ?>"
                            alt="<?= htmlspecialchars(sprintf($translator->translate('student.badges.alt.badge'), (string) ($badge['nome_badge'] ?? ''))) ?>"
                            role="button"
                            tabindex="0"
                            data-toggle="popover"
                            data-trigger="focus"
                            data-placement="top"
                            data-html="false"
                            title="<?= htmlspecialchars((string) ($badge['nome_badge'] ?? '')) ?>"
                            data-content="<?= htmlspecialchars((string) ($badge['descrizione'] ?? '')) ?>"
                        />
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
