<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$plugin = is_array($plugin ?? null) ? $plugin : [];
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 text-gray-800 mb-3"><?= htmlspecialchars((string) ($plugin['nome_plugin'] ?? $pluginCode ?? 'Plugin')) ?></h1>
            <p class="mb-0"><?= $translator->translate('plugin.page.placeholder.student') ?></p>
        </div>
    </div>
</div>
