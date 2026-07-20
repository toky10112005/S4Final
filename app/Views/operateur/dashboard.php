<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Operateur Dashboard</h1>
    <a href="/operateur/prefixes">Configuration des Préfixes</a>
    <a href="/operateur/situations">Situations des comptes</a>
    <a href="/operateur/baremes">Liste des Barems</a>

    <div>
        <div>
            <h1>Situation gain (retrait)</h1>
                <p>Total gains: <?= $gainretrais['total_gains'] ?? 0 ?></p>
        </div>
        <div>
            <h1>Situation gain (transfert)</h1>
                <p>Total gains: <?= $gainstransfert['total_gains'] ?? 0 ?></p>
        </div>
    </div>


</body>
</html>