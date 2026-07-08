<?php

use App\Service\Flash;
use App\Service\TranslationService;

// Servizio traduzioni per etichette e titoli pagina.
$translator = new TranslationService();
// Messaggi flash eventualmente impostati da redirect login.
$flashes = Flash::all();
?>

    <div class="container login-container">
        <div class="login-card">

            <!-- Sezione sinistra con immagine di contesto studenti. -->
            <div class="logo-section">
                <img src="../assets/images/login_studenti.png" alt="Login Studenti CronoQuest" class="logo-img">
            </div>

            <!-- Sezione destra con form di autenticazione. -->
            <div class="form-section">
                <form class="form-signin" role="form" action="/loginStud" method="POST">
                    <h2 class="form-signin-heading">ChronoQuest Login <?= $translator->translate('studente') ?></h2>

                    <!-- Visualizza alert derivati da tentativi precedenti. -->
                    <?php foreach ($flashes as $f): ?>
                        <div class="alert alert-<?= htmlspecialchars($f['type']) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($translator->translate($f['message'])) ?>
                        </div>
                    <?php endforeach; ?>

                    <!-- Username studente. -->
                    <label for="username" class="sr-only">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>

                    <!-- Password studente. -->
                    <label for="pass" class="sr-only">Password</label>
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>

                    <!-- Pulsante invio login. -->
                    <button class="btn btn-lg btn-primary btn-block w-100" type="submit"><?= $translator->translate('btn.entry') ?></button>
                </form>
            </div>
        </div>
    </div>

