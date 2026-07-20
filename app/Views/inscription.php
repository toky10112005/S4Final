<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f7fb;
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
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Inscription</h2>

                        <form action="/inscription" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
=======
<?php $pageTitle = 'Inscription utilisateur'; $pageSubtitle = 'Accès classique pour les comptes non-client'; $active = 'inscription'; include APPPATH . 'Views/partials/mobile_money_header.php'; ?>

<div class="split">
    <section class="card">
        <h2>Créer un compte</h2>
        <p class="muted">Formulaire classique conservé pour les besoins existants du projet.</p>
        <form action="/inscription" method="post">
            <?= csrf_field() ?>
            <label for="username">Nom d'utilisateur
                <input type="text" id="username" name="username" required>
            </label>
            <label for="email">Email
                <input type="email" id="email" name="email" required>
            </label>
            <label for="password">Mot de passe
                <input type="password" id="password" name="password" required>
            </label>
            <button type="submit">Inscription</button>
        </form>
    </section>

    <section class="card">
        <h2>Parcours recommandé</h2>
        <div class="stats">
            <div class="stat"><small>Client mobile money</small><strong>Numéro</strong></div>
            <div class="stat"><small>Utilisateur classique</small><strong>Email</strong></div>
            <div class="stat"><small>Interface</small><strong>Uniforme</strong></div>
        </div>
        <div class="actions" style="margin-top:16px;">
            <a href="/" class="secondary-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Retour connexion client</a>
            <a href="/client/dashboard" style="padding:12px 14px;border-radius:14px;display:inline-block;background:rgba(255,255,255,.06);border:1px solid var(--border);">Espace client</a>
        </div>
    </section>
</div>

<?php include APPPATH . 'Views/partials/mobile_money_footer.php'; ?>
>>>>>>> 797908dba24c199d1904869ac0481615829a1f54
