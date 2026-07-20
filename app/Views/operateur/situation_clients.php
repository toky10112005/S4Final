<div class="container mt-4">
    <h2>Situation des comptes clients</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Client</th>
                <th>Téléphone</th>
                <th>Solde (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($comptes)): ?>
                <?php foreach ($comptes as $c): ?>
                    <tr>
                        <td><?= esc($c['client_id']) ?></td>
                        <td><?= esc($c['telephone']) ?></td>
                        <td><?= number_format($c['solde'], 2, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Affichage automatique des boutons de pagination -->
    <div class="d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>
</div>