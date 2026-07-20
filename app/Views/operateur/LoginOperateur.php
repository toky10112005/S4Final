<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --card: #1f2937;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #22c55e;
            --accent-2: #38bdf8;
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-wrap {
            width: min(100%, 460px);
        }
        .auth-card {
            background: rgba(17,24,39,.88);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(0,0,0,.28);
            color: var(--text);
            backdrop-filter: blur(8px);
        }
        .form-control {
            background: rgba(15,23,42,.9);
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #16a34a);
            border: 0;
            color: #04110a;
            font-weight: 700;
        }
        .muted { color: var(--muted); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="text-center mb-2">Connexion opérateur</h2>
                        <p class="text-center muted mb-4">Accédez au tableau de bord et aux outils de gestion.</p>
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error')) ?></div>
                        <?php endif; ?>
                        <form action="/login/operateur" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>