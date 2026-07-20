<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            font-family: Arial, sans-serif;
        }
        .auth-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card">
                    <div class="card-body p-4">
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>

                        <h2 class="text-center mb-4">Connexion</h2>
                        <a href="/login/operateur" class="btn btn-outline-secondary w-100 mb-3">Côté Opérateur</a>

                        <form action="/login/client" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone / Username</label>
                                <input type="text" id="telephone" name="telephone" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="/inscription">Créer un compte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
=======
<?php $pageTitle = 'Connexion client'; $pageSubtitle = 'Accès rapide par numéro de téléphone'; $active = 'home'; include APPPATH . 'Views/partials/mobile_money_header.php'; ?>

<div class="grid">
    <section class="card">
        <h2>Connexion automatique</h2>
        <p class="muted">Saisissez votre numéro. Le compte est créé automatiquement s’il n’existe pas encore.</p>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="flash error"><?php echo session()->getFlashdata('error'); ?></div>
        <?php endif; ?>
        <form action="/login/client" method="post">
            <?= csrf_field() ?>
            <label for="telephone">Numéro de téléphone
                <input type="text" id="telephone" name="telephone" placeholder="0331234567" required>
            </label>
            <button type="submit">Se connecter</button>
        </form>
    </section>

    <section class="card">
        <h2>Accès rapides</h2>
        <div class="actions">
            <a class="secondary-btn" href="/inscription" style="padding:12px 14px;border-radius:14px;display:inline-block;">Créer un compte classique</a>
            <a class="danger-btn" href="/login/operateur" style="padding:12px 14px;border-radius:14px;display:inline-block;">Espace opérateur</a>
            <a href="/client/dashboard" style="padding:12px 14px;border-radius:14px;display:inline-block;background:rgba(255,255,255,.06);border:1px solid var(--border);">Voir l’espace client</a>
        </div>
        <div class="stats">
            <div class="stat"><small>Connexion</small><strong>1 champ</strong></div>
            <div class="stat"><small>Création</small><strong>Automatique</strong></div>
            <div class="stat"><small>Solde</small><strong>Instantané</strong></div>
        </div>
    </section>
</div>

<?php include APPPATH . 'Views/partials/mobile_money_footer.php'; ?>
>>>>>>> 797908dba24c199d1904869ac0481615829a1f54
