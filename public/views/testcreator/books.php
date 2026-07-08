<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$books = is_array($books ?? null) ? $books : [];
$userDisplayName = (string) ($userDisplayName ?? '');
$displayName = $userDisplayName !== '' ? $userDisplayName : $translator->translate('testcreator.index.teacher_fallback');
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <div class="container" style="background-color: #073822;">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-2 text-center mb-3 mb-sm-0">
                        <img src="/assets/images/cronoquest_verde.png" alt="<?= htmlspecialchars($translator->translate('testcreator.index.logo_alt')) ?>" class="img-fluid" style="max-height: 90px;" />
                    </div>
                    <div class="col-12 col-sm-10 text-sm-start text-center">
                        <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.books.title') ?></h3>
                        <small class="text-white"><?= $translator->translate('testcreator.books.subtitle') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">
                <?= htmlspecialchars(sprintf($translator->translate('testcreator.subjects.greeting'), $displayName)) ?>
            </h5>
            <button
                type="button"
                class="btn btn-success js-open-book-modal"
                data-book-id="0"
                data-toggle="modal"
                data-target="#bookModal">
                <?= $translator->translate('testcreator.books.add_book') ?>
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('testcreator.books.card_title') ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="testCreatorBooksTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?= $translator->translate('testcreator.questions.list.book') ?></th>
                            <th><?= $translator->translate('testcreator.books.publisher') ?></th>
                            <th><?= $translator->translate('testcreator.books.authors') ?></th>
                            <th><?= $translator->translate('testcreator.subjects.status') ?></th>
                            <th style="width: 260px;"><?= $translator->translate('testcreator.index.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($books as $book): ?>
                            <?php
                            $bookId = (int) ($book['id_libro_testo'] ?? 0);
                            $title = (string) ($book['titolo_libro'] ?? '');
                            $publisher = (string) ($book['casa_editrice'] ?? '');
                            $authors = (string) ($book['autori'] ?? '');
                            $disabled = ((int) ($book['disattivato'] ?? 0)) === 1;
                            ?>
                            <tr class="<?= $disabled ? 'table-danger' : '' ?>">
                                <td><?= htmlspecialchars($title) ?></td>
                                <td><?= htmlspecialchars($publisher) ?></td>
                                <td><?= htmlspecialchars($authors) ?></td>
                                <td>
                                    <?php if ($disabled): ?>
                                        <span class="badge bg-danger"><?= $translator->translate('testcreator.books.disabled') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $translator->translate('testcreator.books.active') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-md-start">
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm js-open-book-modal"
                                        data-book-id="<?= $bookId ?>"
                                        data-toggle="modal"
                                        data-target="#bookModal">
                                        <?= $translator->translate('testcreator.subjects.edit') ?>
                                    </button>

                                    <?php if (!$disabled): ?>
                                        <form method="POST" action="/testcreator/libri/<?= $bookId ?>/disattiva" class="d-inline js-deactivate-book-form">
                                            <button type="submit" class="btn btn-danger btn-sm" data-book-title="<?= htmlspecialchars($title, ENT_QUOTES) ?>">
                                                <?= $translator->translate('testcreator.books.deactivate') ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="/testcreator/libri/save" id="bookSaveForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookModalLabel"><?= $translator->translate('testcreator.books.modal.edit_title') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?= htmlspecialchars($translator->translate('testcreator.subjects.modal.close_aria')) ?>"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_libro_testo" id="book-id" value="0">
                            <div class="mb-3">
                                <label for="book-title" class="form-label"><?= $translator->translate('testcreator.books.field.title') ?></label>
                                <input type="text" class="form-control" id="book-title" name="titolo_libro" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="book-publisher" class="form-label"><?= $translator->translate('testcreator.books.publisher') ?></label>
                                <input type="text" class="form-control" id="book-publisher" name="casa_editrice" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="book-authors" class="form-label"><?= $translator->translate('testcreator.books.authors') ?></label>
                                <input type="text" class="form-control" id="book-authors" name="autori" maxlength="255" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('testcreator.subjects.modal.close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= $translator->translate('testcreator.subjects.modal.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
