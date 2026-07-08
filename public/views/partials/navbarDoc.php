<?php

use App\Service\PluginManagerService;

$pluginMenuItems = (new PluginManagerService())->getTeacherMenuItems();
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Logo a sinistra -->
    <a class=" d-flex align-items-center justify-content-center" href="/docenti/dashboard">
        <div style="margin:0 auto">
            <img src="/assets/images/cronoquest.png" style="display:block;float:left;width:100%;height:100%;" />
        </div>
        
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="/docenti/classi">
            <i class="fas fa-fw fa-school"></i>
            <span><?= $translator->translate('student.nav.classes') ?></span></a>
    </li>

     <!-- Nav Item - Dashboard iniziale-->
    <li class="nav-item">
        <a class="nav-link" href="/docenti/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>

    <!-- Nav Item - Menu studenti e docenti della classe -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span><?= $translator->translate('teacher.nav.stud_teach') ?></span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/docenti/studenti"><?= $translator->translate('teacher.nav.stud_list') ?></a>
                <a class="collapse-item" href="/docenti/docenti"><?= $translator->translate('teacher.nav.doc_list') ?></a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Gestione squadre -->
    <li class="nav-item">
        <a class="nav-link" href="/docenti/squadre">
            <i class="fas fa-fw fa-users"></i>
            <span><?= $translator->translate('teacher.nav.teams') ?></span>
        </a>
    </li>

    <!-- Nav Item - Menu per le quest -->
    <li class="nav-item">
        <a class="nav-link" href="/docenti/quest">
            <i class="fas fa-fw fa-map"></i>
            <span>Quest</span>
        </a>
    </li>
    
    <!-- Nav Item - Menu per i Badge -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBadges"
            aria-expanded="true" aria-controls="collapseBadges">
            <i class="fas fa-fw fa-trophy"></i>
            <span>Badge</span>
        </a>
        <div id="collapseBadges" class="collapse" aria-labelledby="headingBadges"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/docenti/badge"><?= $translator->translate('teacher.nav.see_badge') ?></a>
                <a class="collapse-item" href="/docenti/badge/assegnati"><?= $translator->translate('teacher.nav.stud_badge') ?></a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Menu per i poteri degli studenti -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePowers"
            aria-expanded="true" aria-controls="collapsePowers">
            <i class="fas fa-fw fa-bolt"></i>
            <span><?= $translator->translate('teacher.nav.powers') ?></span>
        </a>
        <div id="collapsePowers" class="collapse" aria-labelledby="headingPowers"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/docenti/poteri"><?= $translator->translate('teacher.nav.see_powers') ?></a>
                                <a class="collapse-item" href="/docenti/poteri/assegnati"><?= $translator->translate('teacher.nav.assigned_powers') ?></a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Menu per la creazione e gestione dei personaggi -->
    <li class="nav-item">
        <a class="nav-link" href="/docenti/personaggi">
            <i class="fas fa-fw fa-user"></i>
            <span><?= $translator->translate('teacher.nav.character') ?></span>
        </a>
    </li>
    
    <!-- Nav Item - Menu per le punizioni degli studenti -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePledges"
            aria-expanded="true" aria-controls="collapsePledges">
            <i class="fas fa-fw fa-skull"></i>
            <span><?= $translator->translate('teacher.nav.punishment') ?></span>
        </a>
        <div id="collapsePledges" class="collapse" aria-labelledby="headingPledges"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/docenti/punizioni"><?= $translator->translate('teacher.nav.see_punishment') ?></a>
                <a class="collapse-item" href="/docenti/punizioni/assegnate"><?= $translator->translate('teacher.nav.assigned_punishment') ?></a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Menu per il negozio e le personalizzazioni -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePersonal"
            aria-expanded="true" aria-controls="collapsePersonal">
            <i class="fas fa-fw fa-store"></i>
            <span><?= $translator->translate('teacher.nav.customizations') ?></span>
        </a>
        <div id="collapsePersonal" class="collapse" aria-labelledby="headingPersonal"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/docenti/personalizzazioni"><?= $translator->translate('teacher.nav.customizations') ?></a>
                <a class="collapse-item" href="/docenti/personalizzazioni/set">Set</a>
                <a class="collapse-item" href="/docenti/personalizzazioni/giornate-sconti"><?= $translator->translate('teacher.nav.daily_discounts') ?></a>
                <a class="collapse-item" href="/docenti/personalizzazioni/studenti"><?= $translator->translate('teacher.nav.student_loaded') ?></a>
                <a class="collapse-item" href="/docenti/personalizzazioni/in-uso"><?= $translator->translate('teacher.nav.used') ?></a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Menu per i materiali da allegare agli studenti -->
    <li class="nav-item">
        <a class="nav-link" href="/docenti/materiali">
            <i class="fas fa-fw fa-book"></i>
            <span><?= $translator->translate('teacher.nav.material') ?></span>
        </a>
    </li>

    <?php if ($pluginMenuItems !== []): ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            <?= htmlspecialchars($translator->translate('plugin.nav.heading')) ?>
        </div>
        <?php foreach ($pluginMenuItems as $pluginItem): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= htmlspecialchars((string) ($pluginItem['url'] ?? '#')) ?>">
                    <i class="<?= htmlspecialchars((string) ($pluginItem['icon'] ?? 'fas fa-fw fa-puzzle-piece')) ?>"></i>
                    <span><?= htmlspecialchars((string) ($pluginItem['label'] ?? 'Plugin')) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
