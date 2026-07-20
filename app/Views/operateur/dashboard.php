<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }
        .card-shadow {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
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