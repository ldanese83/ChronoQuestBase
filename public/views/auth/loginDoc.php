<?php

use App\Service\Flash;
use App\Service\TranslationService;

// Traduce etichette pagina login docenti.
$translator = new TranslationService();
// Recupera eventuali messaggi di errore/successo da sessione.
$flashes = Flash::all();
?>
<div class="container login-container">
    <div class="login-card">

        <!-- Colonna logo illustrativo lato sinistro. -->
        <div class="logo-section">
            <img src="./assets/images/login_docenti.png" alt="Login CronoQuest" class="logo-img">
        </div>

        <!-- Colonna destra con form autenticazione docenti. -->
        <div class="form-section">
            <form class="form-signin" role="form" action="/loginDoc" method="POST">
                <h2 class="form-signin-heading">ChronoQuest Login <?= $translator->translate('docente') ?></h2>

                <!-- Rendering messaggi flash provenienti dal controller. -->
                <?php foreach ($flashes as $f): ?>
                    <div class="alert alert-<?= htmlspecialchars($f['type']) ?> alert-dismissible fade show" role="alert">
                        <?= ($translator->translate($f['message'])) ?>
                    </div>
                <?php endforeach; ?>

                <!-- Campo username richiesto. -->
                <label for="inputUser" class="visually-hidden">Username</label>
                <input type="text" id="inputUser" name="inputUser" class="form-control" placeholder="Username" required autofocus>

                <!-- Campo password richiesto. -->
                <label for="inputPassword" class="visually-hidden">Password</label>
                <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>

                <!-- Submit login. -->
                <button class="btn btn-lg btn-primary btn-block w-100" type="submit"><?= $translator->translate('btn.entry') ?></button>
            </form>

            <!-- Link rapido alla registrazione docente. -->
            <div class="register-link">
                <?= $translator->translate('register.not') ?> <a href="/registrazioneDoc"><?= $translator->translate('register.invitation') ?></a>
            </div>
        </div>
    </div>
</div>
