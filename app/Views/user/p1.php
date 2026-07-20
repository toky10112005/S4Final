<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord utilisateur</title>
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
    <div class="container py-5">
        <div class="card card-shadow">
            <div class="card-body text-center p-5">
                <h1 class="mb-3">Tableau de bord utilisateur</h1>
                <p class="text-muted">Bienvenue sur votre espace utilisateur.</p>
            </div>
        </div>
    </div>
</body>
</html>
=======
<?php $pageTitle = 'Tableau de bord utilisateur'; $pageSubtitle = 'Interface unifiée'; $active = 'client'; include APPPATH . 'Views/partials/mobile_money_header.php'; ?>

<div class="grid">
    <section class="card">
        <h2>Tableau de bord utilisateur</h2>
        <p class="muted">Cette page conserve son fonctionnement, avec une interface alignée sur le reste de la simulation.</p>
        <div class="stats">
            <div class="stat"><small>Interface</small><strong>Commune</strong></div>
            <div class="stat"><small>Navigation</small><strong>Harmonisée</strong></div>
            <div class="stat"><small>Style</small><strong>Unifié</strong></div>
        </div>
    </section>
    <section class="card">
        <h2>Liens rapides</h2>
        <div class="actions">
            <a href="/" class="secondary-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Connexion client</a>
            <a href="/inscription" style="padding:12px 14px;border-radius:14px;display:inline-block;background:rgba(255,255,255,.06);border:1px solid var(--border);">Inscription</a>
            <a href="/client/dashboard" class="danger-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Espace client</a>
        </div>
    </section>
</div>

<?php include APPPATH . 'Views/partials/mobile_money_footer.php'; ?>
>>>>>>> 797908dba24c199d1904869ac0481615829a1f54
