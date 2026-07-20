<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Préfixes</title>
</head>
<body>

    <?php if (session()->get('success')): ?>
        <div class="alert alert-success">
            <?= session()->get('success') ?>
        </div>
    <?php endif; ?>

    <h1>Liste des Préfixes</h1>

    <h1> Tsy mbola vita Ajouter un préfixe:</h1>
    <form method="post" action="/operateur/prefixes/ajouter">
        <input type="text" id="prefixe" name="prefixe" required>
        <button type="submit">Ajouter</button>
    </form>

    <?php foreach ($prefixes as $prefix): ?>
        <p><?= esc($prefix['prefixe']) ?></p><button type="button" onclick="supprimerPrefixe('<?= esc($prefix['prefixe']) ?>')">Supprimer</button>
    <?php endforeach; ?>


    <script>
        function supprimerPrefixe(prefixe) {
            if (confirm('Êtes-vous sûr de vouloir supprimer le préfixe ' + prefixe + ' ?')) {
                window.location.href = '/operateur/prefixes/supprimer/' + encodeURIComponent(prefixe);
            }
        }
    </script>

</body>
</html>