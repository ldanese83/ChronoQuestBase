<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$characters = $characters ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.characters.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.characters.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <button type="button" class="btn btn-sm btn-success shadow-sm" id="openCreateCharacterModal">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('teacher.characters.button.add') ?>
            </button>
            <a href="/docenti/personaggi/import-export" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-file-import fa-sm text-white-50 me-1"></i>
                <?= $translator->translate('teacher.characters.button.import_export') ?>
            </a>
        </div>

        <div id="character-management-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.characters.section.available') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="characterTable" data-page-length="10" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:20%"><?= $translator->translate('teacher.characters.field.name') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.characters.field.life') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.characters.field.mana') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.characters.field.image') ?></th>
                                <th style="width:20%"><?= $translator->translate('teacher.characters.field.edit') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($characters as $character): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($character['nome_personaggio'] ?? '')) ?></td>
                                    <td class="text-center"><?php for($i=1;$i<=(int) ($character['vita_iniziale'] ?? 1);$i++) {
                                                echo "<i class='fas fa-heart fa-sm fa-fw mr-2 text-red-900'></i>";
                                            }  ?></td>
                                    <td class="text-center"><?php for($i=1;$i<=(int) ($character['mana_iniziale'] ?? 1);$i++) {
                                                echo "<i class='fas fa-yin-yang fa-sm fa-fw mr-2 text-blue-900'></i>";
                                            } ?>
                                        </td>
                                    <td class="text-center">
                                        <?php if (!empty($character['immagine'])): ?>
                                            <img
                                                src="<?= htmlspecialchars((string) $character['immagine']) ?>"
                                                alt="<?= htmlspecialchars($translator->translate('teacher.characters.alt.avatar')) ?>"
                                                style="max-width: 130px; max-height: 90px; border:1px solid <?= htmlspecialchars((string) ($character['bordercolor'] ?? '#efefef')) ?>; box-shadow: 2px 2px 4px 2px <?= htmlspecialchars((string) ($character['color'] ?? 'gray')) ?>;">
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('teacher.characters.no_image') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm js-edit-character"
                                            data-character-id="<?= (int) ($character['id_personaggio'] ?? 0) ?>"
                                            data-character-name="<?= htmlspecialchars((string) ($character['nome_personaggio'] ?? ''), ENT_QUOTES) ?>"
                                            data-character-life="<?= (int) ($character['vita_iniziale'] ?? 1) ?>"
                                            data-character-mana="<?=(int) ($character['mana_iniziale'] ?? 1) ?>"
                                            data-character-description="<?= htmlspecialchars((string) ($character['descrizione'] ?? ''), ENT_QUOTES) ?>"
                                            data-character-color="<?= htmlspecialchars((string) ($character['color'] ?? '#808080'), ENT_QUOTES) ?>"
                                            data-character-bordercolor="<?= htmlspecialchars((string) ($character['bordercolor'] ?? '#efefef'), ENT_QUOTES) ?>">
                                            <i class="fas fa-pen fa-sm text-white-50 me-1"></i><?= $translator->translate('common.edit') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="characterFormModal" tabindex="-1" role="dialog" aria-labelledby="characterFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="characterForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="characterFormModalLabel"><?= $translator->translate('teacher.characters.modal.add_title') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="characterId" name="id_personaggio" value="0">

                            <div class="form-group">
                                <label for="characterName"><?= $translator->translate('teacher.characters.field.character_name') ?></label>
                                <input style="width:100%" type="text" class="form-control" id="characterName" name="nome_personaggio" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="characterLife"><?= $translator->translate('teacher.characters.field.initial_life') ?></label>
                                    <input type="number" min="1" class="form-control" id="characterLife" name="vita_iniziale" value="1" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="characterMana"><?= $translator->translate('teacher.characters.field.initial_mana') ?></label>
                                    <input type="number" min="1" class="form-control" id="characterMana" name="mana_iniziale" value="1" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="characterColor"><?= $translator->translate('teacher.characters.field.shadow_color') ?></label>
                                    <input type="color" class="form-control" id="characterColor" name="color" value="#808080">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="characterBorderColor"><?= $translator->translate('teacher.characters.field.border_color') ?></label>
                                    <input type="color" class="form-control" id="characterBorderColor" name="bordercolor" value="#efefef">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="characterDescription"><?= $translator->translate('teacher.characters.field.description') ?></label>
                                <textarea class="form-control" id="characterDescription" name="descrizione" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="characterImage"><?= $translator->translate('teacher.characters.field.avatar') ?></label>
                                <input style="width:100%" type="file" class="form-control-file" id="characterImage" name="immagine" accept=".jpg,.jpeg,.png,.gif,.webp">
                                <!--<small class="form-text text-muted">File salvato in <code>assets/images/Personaggi</code>.</small>-->
                            </div>

                            <div class="form-group mb-0">
                                <label for="characterNoBgImage"><?= $translator->translate('teacher.characters.field.avatar_no_bg') ?></label>
                                <input style="width:100%" type="file" class="form-control-file" id="characterNoBgImage" name="img_senza_sfondo" accept=".jpg,.jpeg,.png,.gif,.webp">
                                <!--<small class="form-text text-muted">File salvato in <code>assets/images/Personaggi</code>.</small>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveCharacterButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
