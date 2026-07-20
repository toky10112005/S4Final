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
