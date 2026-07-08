<?php if ($rows === []): ?>
    <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.no_results') ?></p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th><?= $translator->translate('plugin.corse.participant') ?></th>
                <th><?= $translator->translate('plugin.corse.points') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars((string) ($row['nome_partecipante'] ?? '')) ?></td>
                    <td><?= (int) ($row['punti'] ?? 0) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
