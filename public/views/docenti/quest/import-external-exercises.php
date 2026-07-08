<?php
use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quest = $quest ?? null;
$externalExercises = $externalExercises ?? [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
?>
<div class="container-fluid">
<?php if ($permissionStatus === PermissionService::STATUS_OK && $quest !== null): ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Esercizi quest: <strong><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></strong></h1>
    <a href="/docenti/quest/import-export/altre-classi" class="btn btn-sm btn-secondary shadow-sm">Indietro</a>
</div>
<div class="card shadow mb-4"><div class="card-body"><div class="table-responsive">
<table class="table table-bordered">
<thead><tr><th>Progressivo</th><th>Esercizio</th><th>Capitolo</th><th>Argomento</th><th>Tipo</th><th>XP</th></tr></thead>
<tbody>
<?php foreach ($externalExercises as $exercise): ?>
<?php
$translatedExerciseType = trim((string) ($exercise['tipo_en'] ?? ''));
$displayExerciseType = $useEnglishDbTranslations && $translatedExerciseType !== ''
    ? $translatedExerciseType
    : (string) ($exercise['tipo'] ?? '');
?>
<tr>
<td><?= (int) ($exercise['progressivo'] ?? 0) ?></td>
<td><?= htmlspecialchars((string) ($exercise['nome_esercizio'] ?? '')) ?></td>
<td><?= htmlspecialchars((string) ($exercise['nome_capitolo'] ?? '')) ?></td>
<td><?= htmlspecialchars((string) ($exercise['nome_argomento'] ?? '')) ?></td>
<td><?= htmlspecialchars($displayExerciseType) ?></td>
<td><?= (int) ($exercise['punti_esperienza'] ?? 0) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table></div></div></div>
<?php endif; ?>
</div>
