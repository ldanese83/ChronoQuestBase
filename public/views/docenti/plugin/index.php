<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$classroom = $classroom ?? null;
$plugins = $plugins ?? [];
$isAdmin = (bool) ($isAdmin ?? false);
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                <?= $translator->translate('plugin.manage.title') ?>
                <?php if (is_array($classroom)): ?>
                    <small class="text-muted">
                        <?= htmlspecialchars((string) ($classroom['nome_classe'] ?? '')) ?>
                        <?= htmlspecialchars((string) ($classroom['anno_scolastico'] ?? '')) ?>
                    </small>
                <?php endif; ?>
            </h1>
        </div>

        <?php if (!is_array($classroom)): ?>
            <div class="alert alert-warning">
                <?= $translator->translate('plugin.manage.no_class') ?>
            </div>
        <?php else: ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.manage.class_plugins') ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/classe">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="width:18%"><?= $translator->translate('plugin.manage.field.active') ?></th>
                                    <th style="width:22%"><?= $translator->translate('plugin.manage.field.plugin') ?></th>
                                    <th style="width:30%"><?= $translator->translate('plugin.manage.field.description') ?></th>
                                    <th style="width:30%"><?= $translator->translate('plugin.manage.field.class_config') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($plugins as $plugin): ?>
                                    <?php
                                    $pluginId = (int) ($plugin['id_plugin'] ?? 0);
                                    $globalActive = ((int) ($plugin['attivo'] ?? 0)) === 1;
                                    $classActive = ((int) ($plugin['classe_attivo'] ?? 0)) === 1;
                                    $classConfig = (string) ($plugin['classe_configurazione_json'] ?? '');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="pluginActive<?= $pluginId ?>"
                                                    name="plugin_attivo[]"
                                                    value="<?= $pluginId ?>"
                                                    <?= $globalActive && $classActive ? 'checked' : '' ?>
                                                    <?= !$globalActive ? 'disabled' : '' ?>>
                                                <label class="form-check-label" for="pluginActive<?= $pluginId ?>">
                                                    <?= $globalActive ? $translator->translate('plugin.manage.enabled_for_class') : $translator->translate('plugin.manage.disabled_globally') ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars((string) ($plugin['nome_plugin'] ?? '')) ?></strong><br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars((string) ($plugin['codice_plugin'] ?? '')) ?>
                                                <?= htmlspecialchars((string) ($plugin['versione'] ?? '')) ?>
                                            </small>
                                        </td>
                                        <td><?= nl2br(htmlspecialchars((string) ($plugin['descrizione'] ?? ''))) ?></td>
                                        <td>
                                            <textarea
                                                class="form-control"
                                                name="plugin_config[<?= $pluginId ?>]"
                                                rows="4"
                                                placeholder='{"chiave":"valore"}'><?= htmlspecialchars($classConfig) ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if ($plugins === []): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted"><?= $translator->translate('plugin.manage.empty') ?></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i><?= $translator->translate('common.save') ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($isAdmin): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('plugin.manage.admin_create') ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="/docenti/plugin/nuovo" enctype="multipart/form-data">
                        <div class="alert alert-info">
                            <?= $translator->translate('plugin.manage.package_hint') ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="pluginPackage"><?= $translator->translate('plugin.manage.field.package') ?></label>
                            <input class="form-control" type="file" id="pluginPackage" name="plugin_package" accept=".zip" required>
                            <small class="text-muted"><?= $translator->translate('plugin.manage.manifest_hint') ?></small>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-puzzle-piece me-1"></i><?= $translator->translate('plugin.manage.install') ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
