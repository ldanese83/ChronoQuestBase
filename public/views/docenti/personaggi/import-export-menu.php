<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$originalCharactersInClass = $originalCharactersInClass ?? [];
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.characters.import_export.title') ?></h1>
        <a href="/docenti/personaggi" class="btn btn-sm btn-secondary shadow-sm"><?= $translator->translate('teacher.characters.import_export.back_to_characters') ?></a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5><?= $translator->translate('teacher.characters.import_export.export_original_title') ?></h5>
                    <p class="text-muted"><?= $translator->translate('teacher.characters.import_export.export_original_desc') ?></p>
                    <form method="GET" action="/docenti/personaggi/import-export/esporta/0" id="exportCharacterForm">
                        <select class="form-control mb-2" id="exportCharacterSelect" required>
                            <option value=""><?= $translator->translate('teacher.characters.import_export.select_character') ?></option>
                            <?php foreach ($originalCharactersInClass as $character): ?>
                                <option value="<?= (int) ($character['id_personaggio'] ?? 0) ?>"><?= htmlspecialchars((string) ($character['nome_personaggio'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm"><?= $translator->translate('teacher.characters.import_export.export_zip') ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5><?= $translator->translate('teacher.characters.import_export.import_file_title') ?></h5>
                    <p class="text-muted"><?= $translator->translate('teacher.characters.import_export.import_file_desc') ?></p>
                    <form method="POST" action="/docenti/personaggi/import-export/importa-file" enctype="multipart/form-data">
                        <input type="file" class="form-control mb-2" name="character_archive" accept=".zip" required>
                        <button type="submit" class="btn btn-success btn-sm"><?= $translator->translate('teacher.characters.import_export.import_archive') ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5><?= $translator->translate('teacher.characters.import_export.import_other_class_title') ?></h5>
                    <p class="text-muted"><?= $translator->translate('teacher.characters.import_export.import_other_class_desc') ?></p>
                    <a class="btn btn-info btn-sm" href="/docenti/personaggi/import-export/altre-classi"><?= $translator->translate('teacher.characters.import_export.open_list') ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('exportCharacterForm')?.addEventListener('submit', function (event) {
    event.preventDefault();
    const characterId = document.getElementById('exportCharacterSelect').value;
    if (!characterId) {
        return;
    }
    window.location.href = '/docenti/personaggi/import-export/esporta/' + characterId;
});
</script>
