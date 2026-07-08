<?php

use App\Service\TranslationService;
use App\Service\TeacherCommunicationsService;

// Inizializza servizio traduzioni per titolo e stringhe layout.
$translator = new TranslationService();
$viewsPath = __DIR__ . '/..';
$teacherTopbarData = (new TeacherCommunicationsService())->getTopbarData();

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
    <title><?= htmlspecialchars($translator->translate($title) ?? ' - ChronoQuest') ?></title>

    <!-- Dipendenze CSS principali del layout docenti. -->
    <link href="/assets/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
    <link href="/assets/fontawesome-7.2/css/all.min.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/headers.css" rel="stylesheet">
    
    <!-- font di sb-admin2 -->
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Stili aggiuntivi opzionali passati dal controller. -->
    <?php if (!empty($pageStyles ?? [])): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link href="<?= htmlspecialchars($style) ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Navbar dedicata test creator. -->
        <?php require __DIR__ . '/../partials/navbarTestCreator.php'; ?> 

        <!-- barra superiore -->
        <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content" >

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #0a5828;">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        
                        <div id="motto">TEST CREATOR</div>
                        
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            
                            <?php
                            require __DIR__ . '/../partials/alertsDoc.php';
                            require __DIR__ . '/../partials/messagesDoc.php';
                            ?>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    
                                    </span>
                                    <img class="img-profile rounded-circle"
                                        src="/assets/images/undraw_profile_2.svg">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="../classi_all.php">
                                        <i class="fas fa-book fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Creatore Test
                                    </a>
                                    <a class="dropdown-item" href="../dati_amministratore.php">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profilo
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>

                        </ul>

                    </nav>
                    <!-- End of Topbar -->
                <?php require __DIR__ . '/../partials/flash.php'; ?>
                <?= $content ?>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Developed by prof. Danese</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="font-weight:normal">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modali pagina-specifiche opzionali. -->
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

    <!-- Script core front-end condivisi. -->
    <script src="/js/docenti/topbarNotifications.js"></script>
    <script src="/assets/jquery/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="/assets/datatables/dataTables.bootstrap4.min.js"></script>
    

    <!-- Core plugin JavaScript-->
    <script src="/assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    

    <!-- Config JS globale per URL base app. -->
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
