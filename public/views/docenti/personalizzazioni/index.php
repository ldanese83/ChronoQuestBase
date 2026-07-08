<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$personalizations = $personalizations ?? [];
$characters = $characters ?? [];
$filterTypes = $filterTypes ?? [];
$equipmentAbilities = $equipmentAbilities ?? [];
$petAbilities = $petAbilities ?? [];
$personalizationAbilities = $personalizationAbilities ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('teacher.customizations.page.title.class') ?>
                <strong><?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?></strong>
                <span style="font-size:12pt;font-style: italic;">
                    <?= $translator->translate('teacher.customizations.label.year') ?> <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                </span>
            </h1>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#personalizationModal" id="openCreatePersonalizationModal">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.customizations.button.add') ?>
                </button>
                <button type="button" class="btn btn-sm btn-orange shadow-sm" data-toggle="modal" data-target="#costumeModal">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
                    <?= $translator->translate('teacher.customizations.button.add_costume') ?>
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.customizations.section.list') ?></h6>
                <div class="d-flex align-items-center gap-2">
                    <label for="customizationTypeFilter" class="mb-0"><?= $translator->translate('teacher.customizations.filter.type') ?></label>
                    <select id="customizationTypeFilter" class="form-control">
                        <option value=""><?= $translator->translate('teacher.customizations.filter.select_type') ?></option>
                        <?php foreach ($filterTypes as $type): ?>
                            <option value="<?= htmlspecialchars((string) $type) ?>"><?= $translator->translate('teacher.customizations.type.' . (string) $type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="customizationTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= $translator->translate('teacher.customizations.field.name') ?></th>
                                <th><?= $translator->translate('teacher.customizations.field.type') ?></th>
                                <th><?= $translator->translate('teacher.customizations.field.image') ?></th>
                                <th><?= $translator->translate('teacher.customizations.field.cost') ?></th>
                                <th><?= $translator->translate('teacher.customizations.field.character') ?></th>
                                <th><?= $translator->translate('common.edit') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personalizations as $item): ?>
                                <tr data-tipo="<?= htmlspecialchars((string) ($item['tipo'] ?? '')) ?>">
                                    <td><?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? '')) ?></td>
                                    <td><?= $translator->translate('teacher.customizations.type.' . (string) ($item['tipo'] ?? '')) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($item['img'])): ?>
                                            <img src="<?= htmlspecialchars((string) $item['img']) ?>" alt="<?= htmlspecialchars($translator->translate('teacher.customizations.alt.customization')) ?>" style="max-width:120px; max-height:90px;" class="img-thumbnail">
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('teacher.customizations.no_image') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= (int) ($item['costo'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($item['nome_personaggio'] ?? '-')) ?></td>
                                    <td class="text-center">
                                        <?php if ((int) ($item['fk_studente'] ?? 0) === 0): ?>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning shadow-sm js-edit-customization"
                                                data-id="<?= (int) ($item['id_personalizzazione'] ?? 0) ?>"
                                                data-name="<?= htmlspecialchars((string) ($item['nome_personalizzazione'] ?? ''), ENT_QUOTES) ?>"
                                                data-type="<?= htmlspecialchars((string) ($item['tipo'] ?? ''), ENT_QUOTES) ?>"
                                                data-cost="<?= (int) ($item['costo'] ?? 0) ?>"
                                                data-character-id="<?= (int) ($item['fk_personaggio'] ?? 0) ?>"
                                                data-description="<?= htmlspecialchars((string) ($item['descrizione'] ?? ''), ENT_QUOTES) ?>"
                                                data-suffix="<?= htmlspecialchars((string) ($item['suffisso_costume'] ?? ''), ENT_QUOTES) ?>"
                                                data-abilities="<?= htmlspecialchars((string) json_encode($personalizationAbilities[(int) ($item['id_personalizzazione'] ?? 0)] ?? []), ENT_QUOTES) ?>">
                                                <?= $translator->translate('common.edit') ?>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $translator->translate('teacher.customizations.read_only') ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="personalizationModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="/docenti/personalizzazioni/save" enctype="multipart/form-data" id="personalizationForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="personalizationModalLabel"><?= $translator->translate('teacher.customizations.button.add') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_personalizzazione" id="personalizationId" value="0">

                            <div class="form-group">
                                <label for="personalizationName"><?= $translator->translate('teacher.customizations.field.name') ?></label>
                                <input type="text" class="form-control" id="personalizationName" name="nome_personalizzazione" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="personalizationType"><?= $translator->translate('teacher.customizations.field.type') ?></label>
                                    <select class="form-control" id="personalizationType" name="tipo" required>
                                        <?php foreach ($filterTypes as $type): ?>
                                            <option value="<?= htmlspecialchars((string) $type) ?>"><?= $translator->translate('teacher.customizations.type.' . (string) $type) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="personalizationCost"><?= $translator->translate('teacher.customizations.field.cost') ?></label>
                                    <input type="number" min="0" class="form-control" id="personalizationCost" name="costo" value="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="personalizationCharacter"><?= $translator->translate('teacher.customizations.field.character_limited') ?></label>
                                <select class="form-control" id="personalizationCharacter" name="fk_personaggio">
                                    <option value="0"><?= $translator->translate('common.none') ?></option>
                                    <?php foreach ($characters as $character): ?>
                                        <option value="<?= (int) ($character['id_personaggio'] ?? 0) ?>"><?= htmlspecialchars((string) ($character['nome_personaggio'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="personalizationDescription"><?= $translator->translate('teacher.customizations.field.description') ?></label>
                                <textarea class="form-control" id="personalizationDescription" name="descrizione" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="personalizationSuffix"><?= $translator->translate('teacher.customizations.field.costume_suffix') ?></label>
                                <input type="text" class="form-control" id="personalizationSuffix" name="suffisso_costume" placeholder="<?= htmlspecialchars($translator->translate('teacher.customizations.placeholder.costume_suffix')) ?>">
                            </div>
                            <div class="form-group mb-0">
                                <label for="personalizationImage"><?= $translator->translate('teacher.customizations.field.image') ?></label>
                                <input type="file" class="form-control-file" id="personalizationImage" name="img" accept=".jpg,.jpeg,.png,.gif,.webp">
                            </div>

                            <div id="abilitiesContainer" class="mt-3" style="display:none;">
                                <h6 id="abilitiesTitle"><?= $translator->translate('teacher.customizations.abilities.equipment') ?></h6>
                                <select id="abilityOptionsTemplateEquip" style="display:none;">
                                    <option value=""><?= $translator->translate('teacher.customizations.abilities.select') ?></option>
                                    <?php foreach ($equipmentAbilities as $ability): ?>
                                        <?php
                                        $translatedAbilityName = trim((string) ($ability['tipologia_en'] ?? ''));
                                        $displayAbilityName = $useEnglishDbTranslations && $translatedAbilityName !== ''
                                            ? $translatedAbilityName
                                            : (string) ($ability['tipologia'] ?? '');
                                        ?>
                                        <option value="<?= (int) ($ability['id_abilita'] ?? 0) ?>"><?= htmlspecialchars($displayAbilityName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select id="abilityOptionsTemplatePet" style="display:none;">
                                    <option value=""><?= $translator->translate('teacher.customizations.abilities.select') ?></option>
                                    <?php foreach ($petAbilities as $ability): ?>
                                        <?php
                                        $translatedAbilityName = trim((string) ($ability['tipologia_en'] ?? ''));
                                        $displayAbilityName = $useEnglishDbTranslations && $translatedAbilityName !== ''
                                            ? $translatedAbilityName
                                            : (string) ($ability['tipologia'] ?? '');
                                        ?>
                                        <option value="<?= (int) ($ability['id_abilita'] ?? 0) ?>"><?= htmlspecialchars($displayAbilityName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="abilityRows"></div>
                                <input type="hidden" name="abilities" id="personalizationAbilitiesInput" value="[]">
                                <button type="button" class="btn btn-sm btn-info mt-2" id="addAbilityRowButton"><?= $translator->translate('teacher.customizations.abilities.add') ?></button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="costumeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/docenti/personalizzazioni/costume" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= $translator->translate('teacher.customizations.costume.modal_title') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('common.close')) ?>"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <label for="costumeImage"><?= $translator->translate('teacher.customizations.costume.file') ?></label>
                            <input type="file" class="form-control-file" id="costumeImage" name="img_costume" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('teacher.customizations.button.upload') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
