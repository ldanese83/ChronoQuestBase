<?php

use App\Service\StudentCommunicationsService;
use App\Service\StudentNavbarCountersService;
use App\Service\TranslationService;

// Inizializza le traduzioni e prepara il layout principale dell'area studenti.
$translator = new TranslationService();
$viewsPath = __DIR__ . '/..';
$studentTopbarData = [
    'enabled' => false,
    'alerts' => [],
    'messages' => [],
    'alertsCount' => 0,
    'messagesCount' => 0,
    'classId' => null,
];

if (!($disableStudentTopbarData ?? false)) {
    $studentTopbarData = (new StudentCommunicationsService())->getTopbarData();
}


$studentNavbarCounters = [
    'contabadge' => 0,
    'contapowers' => 0,
    'contaesdasvol' => 0,
    'contapundasvol' => 0,
    'contaInvitiSquadra' => 0,
    'contaforzieri' => 0,
];

if (!($disableStudentNavbarCounters ?? false)) {
    $studentNavbarCounters = (new StudentNavbarCountersService())->getCounters();
}

$contabadge = (int) ($studentNavbarCounters['contabadge'] ?? 0);
$contapowers = (int) ($studentNavbarCounters['contapowers'] ?? 0);
$contaesdasvol = (int) ($studentNavbarCounters['contaesdasvol'] ?? 0);
$contapundasvol = (int) ($studentNavbarCounters['contapundasvol'] ?? 0);
$contaInvitiSquadra = (int) ($studentNavbarCounters['contaInvitiSquadra'] ?? 0);
$contaforzieri = (int) ($studentNavbarCounters['contaforzieri'] ?? 0);

$renderPagePartial = static function (string $partial, array $partialData = []) use ($viewsPath): void {
    if (str_contains($partial, '..')) {
        throw new RuntimeException('Il percorso della partial non può contenere riferimenti parent.');
    }

    $partialPath = $viewsPath . '/' . ltrim($partial, '/') . '.php';
    if (!file_exists($partialPath)) {
        throw new RuntimeException("Partial non trovata: {$partialPath}");
    }

    extract($partialData, EXTR_SKIP);
    require $partialPath;
};
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

    <!-- Dipendenze CSS principali del layout studenti, allineate al pattern usato nei docenti. -->
    <link href="/assets/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
    <link href="/assets/fontawesome-7.2/css/all.min.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Stili aggiuntivi opzionali caricati dalla singola pagina. -->
    <?php if (!empty($pageStyles ?? [])): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link href="<?= htmlspecialchars($style) ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body id="page-top">
<div id="wrapper">
    <!-- Sidebar studenti centralizzata in una partial dedicata. -->
    <?php require __DIR__ . '/../partials/navbarStud.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar coerente con l'interfaccia legacy studenti. -->
            <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #0d1b2a;">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <div id="motto"><?= htmlspecialchars($translator->translate('motto')) ?></div>

                <ul class="navbar-nav ml-auto">
                    <?php
                    require __DIR__ . '/../partials/alertsStud.php';
                    require __DIR__ . '/../partials/messagesStud.php';
                    ?>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="studentUserDropdown" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                            <img class="img-profile rounded-circle" src="/assets/images/undraw_profile_2.svg" alt="Profilo studente">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="studentUserDropdown">
                            <a class="dropdown-item" href="/studenti/profilo">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModalStud">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <?php require __DIR__ . '/../partials/flash.php'; ?>
            <?= $content ?>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Developed by prof. Danese</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Modal logout unica per tutte le pagine studenti incluse nel layout. -->
<div class="modal fade" id="logoutModalStud" tabindex="-1" role="dialog" aria-labelledby="logoutModalStudLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalStudLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="font-weight:normal">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="/logoutStud">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($pageModals ?? [])): ?>
    <?php foreach ($pageModals as $modal): ?>
        <?php
        $modalView = is_array($modal) ? ($modal['view'] ?? null) : $modal;
        $modalData = is_array($modal) ? ($modal['data'] ?? []) : [];
        if (is_string($modalView) && $modalView !== '') {
            $renderPagePartial($modalView, is_array($modalData) ? $modalData : []);
        }
        ?>
    <?php endforeach; ?>
<?php endif; ?>

<script src="/assets/jquery/jquery.min.js"></script>
<script src="/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="/assets/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/assets/jquery-easing/jquery.easing.min.js"></script>
<script src="/js/sb-admin-2.min.js"></script>
<script src="/js/studenti/topbarNotifications.js"></script>

<script>
window.CQ = {
    baseUrl: '/',
    i18n: <?= json_encode($translator->all(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
};
window.cqT = function (key, fallback) {
    const translated = window.CQ?.i18n?.[key];
    if (typeof translated === 'string' && translated.length > 0) {
        return translated;
    }
    return typeof fallback === 'string' ? fallback : key;
};
</script>

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

<?php if (!empty($pageScripts ?? [])): ?>
    <?php foreach ($pageScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
