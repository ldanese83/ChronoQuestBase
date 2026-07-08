<?php

use App\Service\Flash;
use App\Service\TranslationService;

// Traduttore usato per label e messaggi errore del form.
$translator = new TranslationService();
// Recupera e consuma eventuali flash per feedback server-side.
$flashes = Flash::all();
// Ripristina dati form precedenti in caso di validazione fallita.
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<div class="registration-wrapper">
    <!-- Area logo/hero della pagina registrazione. -->
    <div class="logo-section"></div>

    <!-- Form registrazione docente. -->
    <div class="form-section">
        <form method="post" action="/registrazioneDoc" class="row g-3 needs-validation" novalidate>
            <!-- Mostra eventuali messaggi flash generali. -->
            <?php foreach ($flashes as $f): ?>
                <div class="alert alert-<?= htmlspecialchars($f['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($translator->translate($f['message'])) ?>
                </div>
            <?php endforeach; ?>

            <!-- Campo nome con fallback su valore precedente. -->
            <div class="col-md-12">
                <label for="validationCustom01" class="form-label"><?= $translator->translate('register.name') ?></label>
                <input type="text" class="form-control" id="validationCustom01" name="validationCustom01" value="<?= htmlspecialchars($old['validationCustom01'] ?? '') ?>" required>
                <div class="invalid-feedback">
                    <?= $translator->translate('register.error_name_required') ?>
                </div>
            </div>

            <!-- Campo cognome con fallback su valore precedente. -->
            <div class="col-md-12">
                <label for="validationCustom02" class="form-label"><?= $translator->translate('register.surname') ?></label>
                <input type="text" class="form-control" id="validationCustom02" name="validationCustom02" value="<?= htmlspecialchars($old['validationCustom02'] ?? '') ?>" required>
                <div class="invalid-feedback">
                    <?= $translator->translate('register.error_surname_required') ?>
                </div>
            </div>

            <!-- Campo username con validazione AJAX on-change. -->
            <div class="col-md-12">
                <label for="validationCustomUsername" class="form-label">Username</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" id="validationCustomUsername" name="validationCustomUsername" aria-describedby="inputGroupPrepend" required onchange="validaUser()" value="<?= htmlspecialchars($old['validationCustomUsername'] ?? '') ?>">
                    <div class="invalid-feedback">
                        <?= $translator->translate('register.error_username_required') ?>
                    </div>
                </div>
            </div>

            <!-- Campo email con validazione AJAX on-change. -->
            <div class="col-md-12">
                <label for="validationCustom03" class="form-label">Email</label>
                <input type="email" class="form-control" id="validationCustom03" name="validationCustom03" value="<?= htmlspecialchars($old['validationCustom03'] ?? '') ?>" required onchange="validaMail()">
                <div class="invalid-feedback" id="validamailtext">
                    <?= $translator->translate('register.error_email_invalid') ?>
                </div>
            </div>

            <!-- Campo password principale. -->
            <div class="col-md-12">
                <label for="validationCustom04" class="form-label">Password</label>
                <input type="password" class="form-control" id="validationCustom04" name="validationCustom04" required>
                <div class="invalid-feedback">
                    <?= $translator->translate('register.error_password_mismatch') ?>
                </div>
            </div>

            <!-- Campo conferma password. -->
            <div class="col-md-12">
                <label for="validationCustom05" class="form-label"><?= $translator->translate('register.confirm') ?> password</label>
                <input type="password" class="form-control" id="validationCustom05" name="validationCustom05" required>
                <div class="invalid-feedback">
                    <?= $translator->translate('register.error_password_mismatch') ?>
                </div>
            </div>

            <!-- Accettazione GDPR obbligatoria per submit. -->
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="invalidCheck" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                        <?= $translator->translate('register.gdpr') ?>
                    </label>
                    <div class="invalid-feedback">
                        <?= $translator->translate('register.error_privacy') ?>
                    </div>
                </div>
            </div>

            <!-- Pulsante invio registrazione. -->
            <div class="col-12">
                <button class="btn btn-primary" type="submit" style="width:100%"><?= $translator->translate('register.invitation') ?></button>
            </div>
        </form>

        <!-- Link di ritorno al login docenti. -->
        <div class="register-link">
            <a href="/">Back to login</a>
        </div>
    </div>
</div>
