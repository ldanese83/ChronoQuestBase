<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$students = $students ?? [];
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?= $translator->translate('dash.studenticlasse') ?>:
            <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
            <span style="font-size:12pt;font-style: italic;">
                <?= $translator->translate('dash.anno') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
            </span>
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:left"><?= $translator->translate('students') ?></h6>
            <div class="mb-3" style="text-align:right;">
                <span class="me-2 fw-bold"><?= $translator->translate('selected') ?></span>

                <button id="btnMail" class="btn btn-primary btn-sm me-2" disabled>
                    <i class="fas fa-envelope"></i> <?= $translator->translate('send.mail') ?>
                </button>

                <button id="btnCuore" class="btn btn-danger btn-sm" disabled>
                    <i class="fas fa-heart-broken"></i> <?= $translator->translate('less.heart') ?>
                </button>
                
                <button id="btnRicompensa" class="btn btn-success btn-sm ml-2" disabled>
                    <i class="fas fa-gift"></i> <?= $translator->translate('give.bonus') ?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width:9%;vertical-align:top;"><?= $translator->translate('register.surname') ?></th>
                            <th style="width:9%;vertical-align:top;"><?= $translator->translate('register.name') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('character.life') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('character.mana') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('character.money') ?></th>
                            <th style="width:4%;vertical-align:top;"><?= $translator->translate('character.level') ?></th>
                            <th style="width:15%;vertical-align:top;"><?= $translator->translate('character.next') ?></th>
                            <th style="width:10%;vertical-align:top;"><?= $translator->translate('character') ?></th>
                            <th style="width:4%;vertical-align:top;"><?= $translator->translate('less.heart') ?></th>
                            <th style="width:4%;vertical-align:top;"><?= $translator->translate('death.instant') ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th><?= $translator->translate('register.surname') ?></th>
                            <th><?= $translator->translate('register.name') ?></th>
                            <th><?= $translator->translate('character.life') ?></th>
                            <th><?= $translator->translate('character.mana') ?></th>
                            <th><?= $translator->translate('character.money') ?></th>
                            <th><?= $translator->translate('character.level') ?></th>
                            <th><?= $translator->translate('character.next') ?></th>
                            <th><?= $translator->translate('character') ?></th>
                            <th><?= $translator->translate('less.heart') ?></th>
                            <th><?= $translator->translate('death.instant') ?></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= (int) $student['id'] ?></td>
                                <td><?= htmlspecialchars($student['surname']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>

                                <?php if (!$student['hasCharacter']): ?>
                                    <td><div class="alert alert-danger mb-0">To be chosen</div></td>
                                    <td><div class="alert alert-danger mb-0">0</div></td>
                                    <td><div class="alert alert-danger mb-0">0</div></td>
                                    <td><div class="alert alert-danger mb-0">//</div></td>
                                    <td><div class="alert alert-danger mb-0">//</div></td>
                                    <td><div class="alert alert-danger mb-0">//</div></td>
                                    <td><div class="alert alert-danger mb-0">//</div></td>
                                    <td><div class="alert alert-danger mb-0">//</div></td>
                                <?php else: ?>
                                    <td>
                                        <?php foreach ($student['lifeIcons'] as $icon): ?>
                                            <i class="<?= htmlspecialchars($icon['classes']) ?>"></i>
                                        <?php endforeach; ?>
                                        <?php foreach ($student['shieldIcons'] as $icon): ?>
                                            <i class="<?= htmlspecialchars($icon['classes']) ?>"></i>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($student['manaIcons'] as $icon): ?>
                                            <i class="<?= htmlspecialchars($icon['classes']) ?>"></i>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?= (int) $student['coins'] ?> <i class="fas fa-coins fa-sm fa-fw mr-2"></i>
                                    </td>
                                    <td><?= htmlspecialchars((string) $student['level']) ?></td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-valuenow="<?= (int) $student['nextLevel']['percent'] ?>" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped bg-warning"
                                                 style="width: <?= (int) $student['nextLevel']['percent'] ?>%"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1"><?= htmlspecialchars($student['nextLevel']['label']) ?></small>
                                    </td>
                                    <td style="text-align:center;">
                                        <?php $image = $student['characterImage']; ?>
                                        <?php if (($image['mode'] ?? '') === 'layered'): ?>
                                            <div class="character-preview-layered">
                                                <img style="<?= htmlspecialchars($image['style']) ?>"
                                                     src="<?= htmlspecialchars((string) ($image['baseSrc'] ?? '')) ?>"
                                                     class="thumb_pers_personalizza2"
                                                     alt="Personaggio base">
                                                <img style="visibility:hidden;"
                                                     class="thumb_pers"
                                                     src="<?= htmlspecialchars((string) ($image['baseSrc'] ?? '')) ?>"
                                                     alt="Ingombro personaggio">
                                                <?php if (!empty($image['backgroundSrc'])): ?>
                                                    <img class="thumb_sfondo2"
                                                         src="<?= htmlspecialchars((string) $image['backgroundSrc']) ?>"
                                                         alt="Sfondo personaggio">
                                                <?php endif; ?>
                                                <?php if (!empty($image['hairSrc'])): ?>
                                                    <img class="thumb_capelli2"
                                                         src="<?= htmlspecialchars((string) $image['hairSrc']) ?>"
                                                         alt="Capelli personaggio">
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <img style="<?= htmlspecialchars((string) ($image['style'] ?? '')) ?>"
                                                 src="<?= htmlspecialchars((string) ($image['src'] ?? '')) ?>"
                                                 class="thumb_pers"
                                                 alt="Personaggio di <?= htmlspecialchars($student['name']) ?> <?= htmlspecialchars($student['surname']) ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="#"
                                           class="btn btn-danger btn-circle btn-lg js-open-heart-modal"
                                           data-toggle="modal"
                                           data-target="#togliCuoreModal"
                                           data-student-id="<?= (int) $student['id'] ?>"
                                           data-student-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                           data-student-surname="<?= htmlspecialchars($student['surname'], ENT_QUOTES) ?>">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="#"
                                           class="btn btn-secondary btn-circle btn-lg js-instant-death"
                                           data-student-id="<?= (int) $student['id'] ?>"
                                           data-student-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                           data-student-surname="<?= htmlspecialchars($student['surname'], ENT_QUOTES) ?>">
                                            <i class="fas fa-skull"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NOT_TEACHER): ?>
        <div class='alert alert-danger'><?= $translator->translate('permission.noteacher') ?></div>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NOT_LOGGED): ?>
        <div class='alert alert-danger'><?= $translator->translate('permission.nologin') ?><a href='/'>LOGIN</a></div>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NO_CLASS): ?>
        <div class='alert alert-danger'><?= $translator->translate('permission.noclass') ?> <strong><a href='/classi'>BACK</a></strong></div>
    <?php elseif ($permissionStatus === PermissionService::STATUS_NOT_CLASS_OWNER): ?>
        <div class='alert alert-danger'><?= $translator->translate('permission.notyourclass') ?> <strong><a href='/classi'>BACK</a></strong></div>
    <?php endif; ?>
</div>
<!-- End of Main Content -->
