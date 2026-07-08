<?php

use App\Service\TranslationService;

// Traduttore usato per testo esito validazione utente.
$translator = new TranslationService();
?>
<div class="container py-4">
    <!-- Branch successo: utente validato tramite link email. -->
    <?php if (!($errore ?? true)): ?>
        <div class="alert alert-success" role="alert">
            <p><strong><?= $translator->translate('validate.user.success.title') ?></strong> <?= $translator->translate('validate.user.success.message') ?></p>
            <p><a href="/"><?= $translator->translate('validate.user.login_link') ?></a></p>
        </div>
    <?php else: ?>
        <!-- Branch errore: codice mancante o non corrispondente. -->
        <div class="alert alert-danger" role="alert">
            <p><strong><?= $translator->translate('validate.user.error.title') ?></strong> <?= $translator->translate('validate.user.error.message') ?></p>
            <p><a href="/"><?= $translator->translate('validate.user.login_link') ?></a></p>
        </div>
    <?php endif; ?>
</div>
