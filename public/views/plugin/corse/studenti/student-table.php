<?php if ($rows === []): ?>
    <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.no_results') ?></p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th><?= $translator->translate('plugin.corse.race') ?></th>
                <th><?= $translator->translate('plugin.corse.position') ?></th>
                <th><?= $translator->translate('plugin.corse.points') ?></th>
                <th><?= $translator->translate('plugin.corse.score') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars((string) ($row['nome_corsa'] ?? '')) ?></td>
                    <td><?= (int) ($row['posizione'] ?? 0) ?></td>
                    <td><?= (int) ($row['punti_classifica'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['punteggio_totale'] ?? '0')) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
