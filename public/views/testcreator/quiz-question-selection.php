<?php

use App\Service\PermissionService;
use App\Service\TranslationService;

$translator = new TranslationService();
$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$quiz = is_array($quiz ?? null) ? $quiz : [];
$questions = is_array($questions ?? null) ? $questions : [];
$selectedQuestionIds = is_array($selectedQuestionIds ?? null) ? $selectedQuestionIds : [];
$useEnglishDbTranslations = ($_SESSION['lang'] ?? 'en') === 'en';
$selectedMap = [];
foreach ($selectedQuestionIds as $id) {
    $selectedMap[(int) $id] = true;
}
$quizId = (int) ($quiz['id_quiz'] ?? 0);
?>
<div class="container-fluid">
    <?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
        <div class="jumbotron text-center" style="background-color: #073822; border-radius: 10px; padding: 20px;">
            <h3 class="mb-0" style="color:#FFD700;"><?= $translator->translate('testcreator.quizzes.selection.title') ?></h3>
        </div>
        <form method="POST" action="/testcreator/quiz/<?= $quizId ?>/domande-selezione">
            <div class="mb-3 d-flex gap-2">
                <a href="/testcreator/quiz" class="btn btn-secondary btn-sm"><?= $translator->translate('testcreator.questions.list.back') ?></a>
                <button type="submit" class="btn btn-primary btn-sm"><?= $translator->translate('testcreator.quizzes.selection.save') ?></button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th><?= $translator->translate('testcreator.questions.list.question') ?></th>
                        <th><?= $translator->translate('testcreator.questions.list.type') ?></th>
                        <th><?= $translator->translate('testcreator.topics.topic') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($questions as $question): ?>
                        <?php
                        $questionId = (int) ($question['id_domanda'] ?? 0);
                        $typeText = $useEnglishDbTranslations && trim((string) ($question['tipo_en'] ?? '')) !== '' ? (string) $question['tipo_en'] : (string) ($question['tipo'] ?? '');
                        ?>
                        <tr>
                            <td><input type="checkbox" name="domande[]" value="<?= $questionId ?>" <?= isset($selectedMap[$questionId]) ? 'checked' : '' ?>></td>
                            <td><?= html_entity_decode((string) ($question['domanda'] ?? ''), ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($typeText) ?></td>
                            <td><?= htmlspecialchars((string) ($question['nome_argomento'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php endif; ?>
</div>
