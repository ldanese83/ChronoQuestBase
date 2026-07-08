<?php

use App\Service\Flash;
use App\Service\TranslationService;

// Traduce i messaggi flash prima del rendering nel layout.
$translator = new TranslationService();
// Legge e consuma i messaggi dalla sessione.
$flashes = Flash::all();
?>

<!-- Cicla tutti i messaggi e mostra alert bootstrap dismissibile. -->
<?php foreach ($flashes as $f): ?>
    <div class="alert alert-<?= htmlspecialchars($f['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($translator->translate($f['message'])) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>
