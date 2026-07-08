<?php if ($activeStandings === []): ?>
    <p class="text-muted mb-0"><?= $translator->translate('plugin.corse.no_results') ?></p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th><?= $translator->translate('plugin.corse.participant') ?></th>
                <th><?= $translator->translate('plugin.corse.deliveries') ?></th>
                <th><?= $translator->translate('plugin.corse.grades') ?></th>
                <th><?= $translator->translate('plugin.corse.bonus') ?></th>
                <th><?= $translator->translate('plugin.corse.score') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($activeStandings as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars((string) ($row['name'] ?? $row['nome_partecipante'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['avg_deliveries'] ?? $row['media_consegne'] ?? '0')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['avg_grades'] ?? $row['media_valutazioni'] ?? '0')) ?></td>
                    <td>+<?= htmlspecialchars((string) (($row['avg_delivery_bonus'] ?? $row['bonus_consegne'] ?? 0) + ($row['avg_grade_bonus'] ?? $row['bonus_valutazioni'] ?? 0))) ?></td>
                    <td><strong><?= htmlspecialchars((string) ($row['score'] ?? $row['punteggio_totale'] ?? '0')) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
