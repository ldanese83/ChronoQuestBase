<?php

use App\Service\TranslationService;

// Inizializza servizio traduzioni per titolo e stringhe layout.
$translator = new TranslationService();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <!-- Meta base pagina responsive e compatibile browser moderni. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Danese Luca">

    <!-- Titolo tradotto in base alla chiave passata dalla view. -->
    <title><?= htmlspecialchars($translator->translate($title) ?? 'ChronoQuest') ?></title>

    <!-- Dipendenze CSS principali del layout docenti. -->
    <link href="/assets/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/fontawesome-7.2/css/all.min.css" rel="stylesheet">

    <!-- Stili aggiuntivi opzionali passati dal controller. -->
    <?php if (!empty($pageStyles ?? [])): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link href="<?= htmlspecialchars($style) ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body> 

<!-- Contenitore principale: flash + contenuto della view corrente. -->
<div class="container-fluid py-3">
    <?= $content ?>
</div>

<!-- Script core front-end condivisi. -->
<script src="/assets/jquery/jquery.min.js"></script>
<script src="/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>

<!-- Config JS globale per URL base app. -->
<script>
window.CQ = {
    baseUrl: '/'
};
</script>

<!-- Caricamento condizionale MathJax per pagine con formule. -->
<?php if (!empty($useMathJax ?? false)): ?>
<script>
window.MathJax = {
    tex: {
        inlineMath: [['\\(', '\\)'], ['$', '$']],
        displayMath: [['\\[', '\\]'], ['$$', '$$']]
    },
    svg: {
        fontCache: 'global'
    }
};
</script>
<script src="/assets/MathJax-master/tex-mml-chtml.js"></script>
<?php endif; ?>

<!-- Script pagina-specifici opzionali. -->
<?php if (!empty($pageScripts ?? [])): ?>
    <?php foreach ($pageScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
