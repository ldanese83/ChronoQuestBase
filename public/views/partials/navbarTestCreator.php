<?php

use App\Service\Session;

$currentUser = Session::get('user');
$currentRole = is_array($currentUser) ? (string) ($currentUser['role'] ?? '') : '';
$isAdmin = $currentRole === 'amministratore';
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Logo a sinistra -->
    <a class="d-flex align-items-center justify-content-center" href="/docenti/dashboard">
        <div style="margin:0 auto">
            <img src="/assets/images/cronoquest_verde.png" style="display:block;float:left;width:100%;height:100%;" alt="ChronoQuest" />
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a href="/testcreator/begin" class="nav-link">
            <i class="fa fa-image fa-compass"></i>Home Page
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/materie" class="nav-link">
            <i class="fa fa-image fa-globe"></i><?= $translator->translate('testcreator.nav.subjects') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/argomenti" class="nav-link">
            <i class="fa fa-image fa-table"></i><?= $translator->translate('testcreator.nav.topics') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/domande" class="nav-link">
            <i class="fa fa-image fa-question"></i><?= $translator->translate('testcreator.nav.questions') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/quiz" class="nav-link">
            <i class="fa fa-image fa-clipboard"></i>Quiz
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/libri" class="nav-link">
            <i class="fa fa-image fa-folder"></i><?= $translator->translate('testcreator.nav.books') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/griglie" class="nav-link">
            <i class="fa fa-image fa-bars"></i><?= $translator->translate('testcreator.nav.grid') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/template-stampa" class="nav-link">
            <i class="fa fa-image fa-code"></i><?= $translator->translate('testcreator.nav.template') ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="/testcreator/import-domande" class="nav-link">
            <i class="fa fa-image fa-arrow-down"></i><?= $translator->translate('testcreator.nav.importq') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/testcreator/esporta-domande">
            <i class="fa fa-image fa-arrow-up"></i><?= $translator->translate('testcreator.nav.exportq') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/docenti/classi">
            <i class="fa fa-image fa-users"></i><?= $translator->translate('student.nav.classes') ?>
        </a>
    </li>

    <?php if ($isAdmin): ?>
        <li class="nav-item">
            <a class="nav-link" href="/testcreator/mail-docenti">
                <i class="fa fa-image fa-envelope"></i><?= $translator->translate('testcreator.nav.teachers_mail') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/testcreator/amministratori">
                <i class="fa fa-image fa-shield"></i><?= $translator->translate('testcreator.nav.admin_management') ?>
            </a>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
