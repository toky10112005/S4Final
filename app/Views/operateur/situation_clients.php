<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des comptes clients</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #22c55e;
            --border: rgba(255,255,255,.08);
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34,197,94,.20), transparent 28%),
                radial-gradient(circle at top right, rgba(56,189,248,.16), transparent 26%),
                linear-gradient(180deg, #0b1020 0%, #0f172a 100%);
            color: var(--text);
            padding: 24px 0;
        }
        .card {
            background: rgba(17,24,39,.86);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
        }
        .table {
            color: var(--text);
        }
        .table-dark {
            --bs-table-bg: rgba(255,255,255,.05);
            color: var(--text);
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