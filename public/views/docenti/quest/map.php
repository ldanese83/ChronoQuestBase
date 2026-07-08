<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapters = $chapters ?? [];
$nextProgressive = $nextProgressive ?? 1;
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                Quest <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong>
            </h1>
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="/docenti/quest" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>Back
                </a>
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="openChapterListButton">
                    <i class="fas fa-list fa-sm text-white-50 me-1"></i><?= $translator->translate('map.chapterlist') ?>
                </button>
                <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/consegne-non-valutate" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                    <i class="fas fa-clipboard-check fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.unevaluated.open') ?>
                </a>
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" id="addChapterButton">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i><?= $translator->translate('map.addchapter') ?>
                </button>
            </div>
        </div>

        <div id="chapter-management-alert" class="d-none"></div>

        <div id="piantina" class="quest-map-wrapper mb-4">
            <div class="quest-map-frame">
            <canvas id="immagine_piantina" width="1000" height="666" data-map-src="<?= htmlspecialchars((string) ($quest['piantina_quest'] ?? '')) ?>" data-create-url="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/capitoli"></canvas>
            </div>
        </div>

        <div class="modal fade" id="addChapterModal" tabindex="-1" role="dialog" aria-labelledby="addChapterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="addChapterForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addChapterModalLabel"><?= $translator->translate('map.newchapter') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Chiudi">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="chapterNameInput"><?= $translator->translate('map.chaptername') ?></label>
                                <input style="width:100%" type="text" class="form-control" id="chapterNameInput" name="nome_capitolo" required>
                            </div>
                            <div class="form-group">
                                <label for="chapterCoordXInput"><?= $translator->translate('map.coord') ?> X</label>
                                <input style="width:100%" type="text" class="form-control" id="chapterCoordXInput" name="coord_x" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="chapterCoordYInput"><?= $translator->translate('map.coord') ?> Y</label>
                                <input style="width:100%" type="text" class="form-control" id="chapterCoordYInput" name="coord_y" readonly required>
                            </div>
                            <div class="form-group mb-0">
                                <label for="chapterProgressiveInput"><?= $translator->translate('map.progressive') ?></label>
                                <input style="width:100%" type="number" min="1" class="form-control" id="chapterProgressiveInput" name="progressivo" value="<?= (int) $nextProgressive ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveChapterButton"><?= $translator->translate('common.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            window.questMapData = {
                chapters: <?= json_encode(array_map(static function (array $chapter): array {
                    return [
                        'id' => (int) ($chapter['id_capitolo'] ?? 0),
                        'x' => (int) ($chapter['coord_x'] ?? 0),
                        'y' => (int) ($chapter['coord_y'] ?? 0),
                        'progressive' => (int) ($chapter['progressivo'] ?? 0),
                    ];
                }, $chapters), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                questId: <?= (int) ($quest['id_quest'] ?? 0) ?>
            };
        </script>
    <?php endif; ?>
</div>
