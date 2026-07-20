<?php $pageTitle = 'Espace opérateur'; $pageSubtitle = 'Interface de supervision et de configuration'; $active = 'operator'; include APPPATH . 'Views/partials/mobile_money_header.php'; ?>

<div class="grid">
    <section class="card">
        <h2>Supervision opérateur</h2>
        <p class="muted">Cette page garde sa logique existante tout en partageant le même design que l’espace client.</p>
        <div class="stats">
            <div class="stat"><small>Gains</small><strong>Frais</strong></div>
            <div class="stat"><small>Comptes</small><strong>Clients</strong></div>
            <div class="stat"><small>Préfixes</small><strong>Réseau</strong></div>
        </div>
    </section>
    <section class="card">
        <h2>Navigation</h2>
        <div class="actions">
            <a href="/" class="secondary-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Connexion client</a>
            <a href="/inscription" style="padding:12px 14px;border-radius:14px;display:inline-block;background:rgba(255,255,255,.06);border:1px solid var(--border);">Inscription</a>
            <a href="/client/dashboard" class="danger-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Espace client</a>
        </div>
    </section>
</div>

<?php include APPPATH . 'Views/partials/mobile_money_footer.php'; ?>