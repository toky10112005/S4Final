<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Préfixes</title>
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