<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des comptes clients</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-4">Situation des comptes clients</h2>

                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
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

                <div class="d-flex justify-content-center mt-3">
                    <?= $pager->links() ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>