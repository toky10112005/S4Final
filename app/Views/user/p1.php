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