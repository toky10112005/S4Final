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
        .container {
            max-width: 1100px;
        }
        .card-shadow {
            background: rgba(17,24,39,.86);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
        }
        .btn-outline-primary {
            border-color: var(--accent-2);
            color: var(--accent-2);
        }
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--accent-2), #0ea5e9);
            color: #02131d;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Tableau de bord opérateur</h1>

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
                        <p class="card-text">Total gains : <?= $gainretrais['total_gains'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">Situation gain (transfert)</h5>
                        <p class="card-text">Total gains : <?= $gainstransfert['total_gains'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>