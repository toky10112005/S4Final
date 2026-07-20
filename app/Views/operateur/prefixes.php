<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Préfixes</title>
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
        .card-shadow {
            background: rgba(17,24,39,.86);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
        }
        .form-control {
            background: rgba(15,23,42,.88);
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #16a34a);
            border: 0;
            color: #04110a;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card card-shadow">
            <div class="card-body">
                <?php if (session()->get('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->get('success') ?>
                    </div>
                <?php endif; ?>

                <h2 class="mb-4">Liste des Préfixes</h2>

                <h5 class="mb-3">Ajouter un préfixe</h5>
                <form method="post" action="/operateur/prefixes/ajouter" class="row g-2 mb-4">
                    <div class="col-md-8">
                        <input type="text" id="prefixe" name="prefixe" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                </form>

                <ul class="list-group">
                    <?php foreach ($prefixes as $prefix): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= esc($prefix['prefixe']) ?></span>
                            <button type="button" class="btn btn-danger btn-sm" onclick="supprimerPrefixe('<?= esc($prefix['prefixe']) ?>')">Supprimer</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function supprimerPrefixe(prefixe) {
            if (confirm('Êtes-vous sûr de vouloir supprimer le préfixe ' + prefixe + ' ?')) {
                window.location.href = '/operateur/prefixes/supprimer/' + encodeURIComponent(prefixe);
            }
        }
    </script>
</body>
</html>