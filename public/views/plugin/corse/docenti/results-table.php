<?php if ($activeStandings === []): ?>
    <p class="text-muted"><?= $translator->translate('plugin.corse.no_results') ?></p>
<?php else: ?>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th><?= $translator->translate('plugin.corse.participant') ?></th>
                <th><?= $translator->translate('plugin.corse.points') ?></th>
                <th><?= $translator->translate('plugin.corse.score') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($activeStandings as $row): ?>
                <tr>
                    <td><?= (int) ($row['posizione'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['nome_partecipante'] ?? '')) ?></td>
                    <td><?= (int) ($row['punti_classifica'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['punteggio_totale'] ?? '0')) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
