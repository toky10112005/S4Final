<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #22c55e;
            --accent-2: #38bdf8;
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
        .container { max-width: 1200px; }
        .card-shadow {
            background: rgba(17,24,39,.88);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
            backdrop-filter: blur(8px);
        }
        .btn-outline-primary {
            border-color: var(--accent-2);
            color: var(--accent-2);
        }
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--accent-2), #0ea5e9);
            color: #02131d;
        }
        .table { color: var(--text); }
        .table-dark {
            --bs-table-bg: rgba(255,255,255,.05);
            color: var(--text);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h1 class="mb-0">Tableau de bord opérateur</h1>
            <a href="/login/operateur" class="btn btn-outline-primary btn-sm">Se déconnecter</a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="/operateur/prefixes" class="btn btn-outline-primary">Configuration des Préfixes</a>
            <a href="/operateur/situations" class="btn btn-outline-primary">Situations des comptes</a>
            <a href="/operateur/baremes" class="btn btn-outline-primary">Liste des Barèmes</a>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Situation gain (retrait)</h5>
                        <p class="card-text">Total gains : <?= number_format($gainretrais['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Situation gain (transfert)</h5>
                        <p class="card-text">Total gains : <?= number_format($gainstransfert['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Gains réseau propre</h5>
                        <p class="card-text">Frais de base : <?= number_format($gainsInterne['total_gains_frais_base'] ?? 0, 2, ',', ' ') ?> Ar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Gains via autres opérateurs</h5>
                        <?php if (!empty($gainsAutresOperateurs)): ?>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($gainsAutresOperateurs as $item): ?>
                                    <li><?= esc($item['operateur']) ?> : <?= number_format($item['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="card-text mb-0">Aucune donnée disponible.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-shadow mt-3">
            <div class="card-body">
                <h5 class="card-title">Montants à envoyer à chaque opérateur</h5>
                <?php if (!empty($compensationOperateurs)): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Opérateur</th>
                                    <th>Transactions</th>
                                    <th>Montant à reverser</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($compensationOperateurs as $item): ?>
                                    <tr>
                                        <td><?= esc($item['operateur_nom']) ?></td>
                                        <td><?= esc($item['nombre_transactions']) ?></td>
                                        <td><?= number_format($item['montant_total_a_reverser'] ?? 0, 2, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="mb-0">Aucune compensation à afficher.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>