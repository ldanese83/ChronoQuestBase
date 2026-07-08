<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$chapters = $chapters ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <h1 class="h3 mb-0 text-gray-800">
                Quest <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong>
                - <?= $translator->translate('teacher.quest.chapter_list.page_title') ?>
            </h1>
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="/docenti/quest/<?= (int) ($quest['id_quest'] ?? 0) ?>/piantina" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i><?= $translator->translate('teacher.quest.chapter_list.back_to_map') ?>
                </a>
            </div>
        </div>

        <div id="chapter-list-alert" class="d-none"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.quest.chapter_list.chapters') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="chapterListTable" data-page-length="25" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:15%"><?= $translator->translate('teacher.quest.chapter_list.field.progressive') ?></th>
                                <th style="width:55%"><?= $translator->translate('teacher.quest.chapter_list.field.title') ?></th>
                                <th style="width:30%"><?= $translator->translate('teacher.quest.chapter_list.field.edit') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chapters as $chapter): ?>
                                <tr>
                                    <td><?= (int) ($chapter['progressivo'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($chapter['nome_capitolo'] ?? '')) ?></td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm js-edit-chapter"
                                            data-chapter-id="<?= (int) ($chapter['id_capitolo'] ?? 0) ?>"
                                            data-chapter-name="<?= htmlspecialchars((string) ($chapter['nome_capitolo'] ?? ''), ENT_QUOTES) ?>"
                                            data-chapter-progressive="<?= (int) ($chapter['progressivo'] ?? 0) ?>"
                                            data-chapter-x="<?= (int) ($chapter['coord_x'] ?? 0) ?>"
                                            data-chapter-y="<?= (int) ($chapter['coord_y'] ?? 0) ?>">
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

        <div class="modal fade" id="editChapterModal" tabindex="-1" role="dialog" aria-labelledby="editChapterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="editChapterForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editChapterModalLabel"><?= $translator->translate('teacher.quest.chapter_list.modal.edit_title') ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="<?= $translator->translate('common.close') ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editChapterId" name="id_capitolo" value="0">
                            <div class="form-group">
                                <label for="editChapterName"><?= $translator->translate('map.chaptername') ?></label>
                                <input style="width:100%" type="text" class="form-control" id="editChapterName" name="nome_capitolo" required>
                            </div>
                            <div class="form-group">
                                <label for="editChapterCoordX"><?= $translator->translate('map.coord') ?> X</label>
                                <input style="width:100%" type="number" min="0" class="form-control" id="editChapterCoordX" name="coord_x" required>
                            </div>
                            <div class="form-group">
                                <label for="editChapterCoordY"><?= $translator->translate('map.coord') ?> Y</label>
                                <input style="width:100%" type="number" min="0" class="form-control" id="editChapterCoordY" name="coord_y" required>
                            </div>
                            <div class="form-group mb-0">
                                <label for="editChapterProgressive"><?= $translator->translate('map.progressive') ?></label>
                                <input style="width:100%" type="number" min="1" class="form-control" id="editChapterProgressive" name="progressivo" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                            <button type="submit" class="btn btn-primary" id="saveChapterChangesButton"><?= $translator->translate('common.save_changes') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            window.questChapterListData = {
                questId: <?= (int) ($quest['id_quest'] ?? 0) ?>
            };
        </script>
    <?php endif; ?>
</div>
