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