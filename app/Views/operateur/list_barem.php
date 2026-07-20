<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barème des Frais</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --text: #e5e7eb;
            --muted: #9ca3af;
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
        .container {
            max-width: 1100px;
        }
        .card {
            background: rgba(17,24,39,.86);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
            padding: 20px;
        }
        .table {
            color: var(--text);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="mb-4">Barème des Frais par Tranche de Montant</h2>

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Type d'Opération</th>
                        <th>Intervalle (Tranche de Montant)</th>
                        <th>Frais Appliqués</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($baremes)): ?>
                        <?php foreach ($baremes as $b): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= esc(ucfirst($b['type_operation_nom'])) ?>
                                    </span>
                                </td>
                                <td>
                                    Entre <strong><?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar</strong>
                                    et <strong><?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</strong>
                                </td>
                                <td>
                                    <strong class="text-danger"><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun barème configuré.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>