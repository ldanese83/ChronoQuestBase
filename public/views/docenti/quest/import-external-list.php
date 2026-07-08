<?php
use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$availableExternalOriginalQuests = $availableExternalOriginalQuests ?? [];
?>
<div class="container-fluid">
<?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quest originali da altre classi</h1>
    <a href="/docenti/quest/import-export" class="btn btn-sm btn-secondary shadow-sm">Indietro</a>
</div>
<div class="card shadow mb-4"><div class="card-body"><div class="table-responsive">
<table class="table table-bordered">
<thead><tr><th>Nome</th><th>Immagine</th><th>Esercizi</th><th>Importa</th></tr></thead><tbody>
<?php foreach ($availableExternalOriginalQuests as $quest): ?>
<tr>
<td><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></td>
<td class="text-center"><?php if (!empty($quest['image_quest'])): ?><img src="<?= htmlspecialchars((string) $quest['image_quest']) ?>" style="max-width:140px"><?php endif; ?></td>
<td class="text-center"><a class="btn btn-sm btn-primary" href="/docenti/quest/import-export/altre-classi/<?= (int) ($quest['id_quest'] ?? 0) ?>/esercizi">Vedi</a></td>
<td class="text-center"><form method="POST" action="/docenti/quest/import-export/altre-classi/<?= (int) ($quest['id_quest'] ?? 0) ?>/importa" onsubmit="return confirm('Importare la quest nella classe corrente?');"><button class="btn btn-sm btn-info" type="submit">Importa</button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
<?php endif; ?>
</div>
