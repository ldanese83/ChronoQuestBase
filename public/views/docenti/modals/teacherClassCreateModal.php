<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$schoolYears = $schoolYears ?? [];
$availableIcons = $availableIcons ?? [];
$classes = $classes ?? [];
?>
<div class="modal fade" id="teacherClassCreateModal" tabindex="-1" role="dialog" aria-labelledby="teacherClassCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="/docenti/classi">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherClassCreateModalLabel">
                        <?= $translator->translate('teacher.classes.create.title') ?>
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="class_name"><?= $translator->translate('teacher.classes.name') ?></label>
                        <input type="text" class="form-control" id="class_name" name="class_name" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="school_year_id"><?= $translator->translate('teacher.classes.year') ?></label>
                        <select class="form-control" id="school_year_id" name="school_year_id" required>
                            <?php foreach ($schoolYears as $year): ?>
                                <option value="<?= (int) $year['id_anno'] ?>">
                                    <?= htmlspecialchars((string) $year['anno_scolastico']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="class_icon"><?= $translator->translate('teacher.classes.icon') ?></label>
                        <select class="form-control" id="class_icon" name="class_icon">
                            <?php foreach ($availableIcons as $icon): ?>
                                <option value="<?= htmlspecialchars($icon) ?>"><?= htmlspecialchars($icon) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="class_color"><?= $translator->translate('teacher.classes.color') ?></label>
                        <input type="color" class="form-control" id="class_color" name="class_color" value="#0d6efd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    <button type="submit" class="btn btn-primary">
                        <?= $translator->translate('teacher.classes.create.submit') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="teacherClassEditModal" tabindex="-1" role="dialog" aria-labelledby="teacherClassEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="/docenti/classi/0/modifica" id="teacherClassEditForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherClassEditModalLabel"><?= $translator->translate('teacher.classes.edit.title') ?></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_class_name"><?= $translator->translate('teacher.classes.name') ?></label>
                        <input type="text" class="form-control" id="edit_class_name" name="class_name" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit_class_icon"><?= $translator->translate('teacher.classes.icon') ?></label>
                        <select class="form-control" id="edit_class_icon" name="class_icon">
                            <?php foreach ($availableIcons as $icon): ?>
                                <option value="<?= htmlspecialchars($icon) ?>"><?= htmlspecialchars($icon) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit_class_color"><?= $translator->translate('teacher.classes.color') ?></label>
                        <input type="color" class="form-control" id="edit_class_color" name="class_color" value="#0d6efd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $translator->translate('common.close') ?></button>
                    <button type="submit" class="btn btn-primary"><?= $translator->translate('common.save_changes') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
