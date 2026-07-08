<?php

use App\Service\Session;
use App\Service\TranslationService;
use App\Service\PluginManagerService;

// Leggiamo la classe selezionata dalla sessione per evidenziare il contesto corrente nel menu studenti.
$translator = new TranslationService();
$pluginManager = new PluginManagerService();
$selectedClass = Session::get('class');
$selectedClassName = is_array($selectedClass) ? ($selectedClass['name'] ?? null) : null;
$pluginMenuItems = $pluginManager->getStudentMenuItems();
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="d-flex align-items-center justify-content-center" href="/studenti/dashboard">
        <div style="margin:0 auto">
            <img src="/assets/images/cronoquest.png" style="display:block;float:left;width:100%;height:100%;" alt="ChronoQuest" />
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="/studenti/dashboard">
            <i class="fas fa-fw fa-school"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.dashboard')) ?></span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        <?= htmlspecialchars($translator->translate('student.nav.menu')) ?>
    </div>

    <!-- Nav Item - Personaggi -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/classe/dashboard">
            <i class="fas fa-fw fa-user"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.my_character')) ?></span>
            </a>
    </li>
    <!-- Nav Item - Quest -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/quest">
            <i class="fas fa-fw fa-map"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.quest')) ?></span>
            <?php
            if(isset($contaesdasvol) and $contaesdasvol>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-warning\">$contaesdasvol</span>";
            ?>
            </a>
    </li>
    <!-- Nav Item - Poteri -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/poteri">
            <i class="fas fa-fw fa-bolt"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.use_powers')) ?></span></a>
    </li>
    <!-- Nav Item - Aggiunta potere -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/poteri/aggiungi">
            <i class="fas fa-fw fa-plus"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.add_power')) ?></span>
            <?php
            if(isset($contapowers) and $contapowers>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-info\">$contapowers</span>";
            ?>
            </a>
    </li>
    <!-- Nav Item - Personalizzazioni -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/personalizzazioni">
            <i class="fas fa-fw fa-shop"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.customize_character')) ?></span>
            </a>
    </li>
    <!-- Nav Item - Compagni di classe -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/compagni">
            <i class="fas fa-fw fa-users"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.classmates')) ?></span></a>
    </li>
    <!-- Nav Item - Squadra -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/squadra">
            <i class="fas fa-fw fa-people-group"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.my_team')) ?></span>
            <?php
            if(isset($contaInvitiSquadra) and $contaInvitiSquadra>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-warning\">$contaInvitiSquadra</span>";
            ?>
            </a>
    </li>
    <!-- Nav Item - Forzieri -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/forzieri">
            <i class="fas fa-fw fa-box-open"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.won_chests')) ?></span>
            <?php
            if(isset($contaforzieri) and $contaforzieri>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-warning\">$contaforzieri</span>";
            ?>
            </a>
    </li>
    <!-- Nav Item - Punizioni -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/punizioni">
            <i class="fas fa-fw fa-lock"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.my_punishments')) ?></span>
            <?php
            if(isset($contapundasvol) and $contapundasvol>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-danger\">$contapundasvol</span>";
            ?>
            </a>
    </li>
    
    <!-- Nav Item - Badge -->
    <li class="nav-item">
        <a class="nav-link" href="/studenti/badge">
            <i class="fas fa-fw fa-trophy"></i>
            <span><?= htmlspecialchars($translator->translate('student.nav.my_badges')) ?></span>
            <?php
            if(isset($contabadge) and $contabadge>0)
                echo "<span style=\"margin-left:0.3vw\" class=\"badge bg-success\">$contabadge</span>";
            ?>
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

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
