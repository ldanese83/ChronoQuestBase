<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$availableExternalOriginalCharacters = $availableExternalOriginalCharacters ?? [];
?>
<div class="container-fluid">
<?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800"><?= $translator->translate('teacher.characters.import_external.title') ?></h1>
        <a href="/docenti/personaggi/import-export" class="btn btn-sm btn-secondary shadow-sm"><?= $translator->translate('common.back') ?></a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('teacher.characters.import_external.section.importable') ?></h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><?= $translator->translate('common.name') ?></th>
                        <th class="text-center"><?= $translator->translate('teacher.characters.field.image') ?></th>
                        <th class="text-center"><?= $translator->translate('teacher.characters.field.life') ?></th>
                        <th class="text-center"><?= $translator->translate('teacher.characters.field.mana') ?></th>
                        <th class="text-center"><?= $translator->translate('common.import') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($availableExternalOriginalCharacters as $character): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($character['nome_personaggio'] ?? '')) ?></td>
                        <td class="text-center">
                            <?php if (!empty($character['immagine'])): ?>
                                <img src="<?= htmlspecialchars((string) $character['immagine']) ?>" style="max-width:140px" alt="<?= htmlspecialchars($translator->translate('teacher.characters.alt.avatar')) ?>">
                            <?php else: ?>
                                <span class="text-muted"><?= $translator->translate('teacher.characters.no_image') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= (int) ($character['vita_iniziale'] ?? 0) ?></td>
                        <td class="text-center"><?= (int) ($character['mana_iniziale'] ?? 0) ?></td>
                        <td class="text-center">
                            <form method="POST" action="/docenti/personaggi/import-export/altre-classi/<?= (int) ($character['id_personaggio'] ?? 0) ?>/importa" onsubmit="return confirm('<?= htmlspecialchars($translator->translate('teacher.characters.import_external.confirm_import'), ENT_QUOTES) ?>');">
                                <button class="btn btn-sm btn-info" type="submit"><?= $translator->translate('common.import') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
</div>
