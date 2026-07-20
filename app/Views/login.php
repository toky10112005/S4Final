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