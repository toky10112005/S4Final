<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Préfixes</title>
</head>
<body>
    <h1>Liste des Préfixes</h1>

    <h1> Tsy mbola vita Ajouter un préfixe:</h1>
    <form method="post" action="/operateur/prefixes">
        <input type="text" id="prefixe" name="prefixe" required>
        <button type="submit">Ajouter</button>
    </form>

    <?php foreach ($prefixes as $prefix): ?>
        <p><?= esc($prefix['prefixe']) ?></p>
    <?php endforeach; ?>
</body>
</html>