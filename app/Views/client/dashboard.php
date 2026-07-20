<?php $pageTitle = 'Espace client'; $pageSubtitle = 'Solde, dépôt, retrait, transfert et historique'; $active = 'client'; include APPPATH . 'Views/partials/mobile_money_header.php'; ?>

<?php if(!empty($error)): ?>
    <div class="flash error"><?= esc($error) ?></div>
<?php endif; ?>
<?php if(!empty($success)): ?>
    <div class="flash success"><?= esc($success) ?></div>
<?php endif; ?>

<div class="stats">
    <div class="stat"><small>Numéro</small><strong><?= esc($client['telephone']) ?></strong></div>
    <div class="stat"><small>Solde actuel</small><strong><?= number_format($solde, 2) ?> Ar</strong></div>
    <div class="stat"><small>Opérations</small><strong><?= count($history) ?></strong></div>
</div>

<div class="split" style="margin-top:18px;">
    <section class="card">
        <h2>Opérations</h2>
        <div class="grid">
            <div class="card" style="background:rgba(255,255,255,.03);">
                <h3>Dépôt</h3>
                <form method="post" action="/client/deposit">
                    <?= csrf_field() ?>
                    <label>Montant
                        <input type="number" name="amount" step="0.01" required>
                    </label>
                    <button type="submit">Déposer</button>
                </form>
            </div>
            <div class="card" style="background:rgba(255,255,255,.03);">
                <h3>Retrait</h3>
                <form method="post" action="/client/withdraw">
                    <?= csrf_field() ?>
                    <label>Montant
                        <input type="number" name="amount" step="0.01" required>
                    </label>
                    <button type="submit">Retirer</button>
                </form>
            </div>
            <div class="card" style="background:rgba(255,255,255,.03); grid-column: 1 / -1;">
                <h3>Transfert</h3>
                <form method="post" action="/client/transfer">
                    <?= csrf_field() ?>
                    <label>Téléphone destinataire
                        <input type="text" name="telephone_to" required>
                    </label>
                    <label>Montant
                        <input type="number" name="amount" step="0.01" required>
                    </label>
                    <button type="submit">Transférer</button>
                </form>
            </div>
        </div>
    </section>

    <aside class="card">
        <h2>Historique récent</h2>
        <?php if(empty($history)): ?>
            <p class="muted">Aucune opération pour le moment.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Frais</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($history as $h): ?>
                        <tr>
                            <td><?= esc($h['date_transaction']) ?></td>
                            <td><?= esc($h['type_nom'] ?? $h['id_type_operation']) ?></td>
                            <td><?= number_format($h['montant'], 2) ?></td>
                            <td><?= number_format($h['frais'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="actions" style="margin-top:16px;">
            <a href="/" class="secondary-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Changer de numéro</a>
            <a href="/client/logout" class="danger-btn" style="padding:12px 14px;border-radius:14px;display:inline-block;">Déconnexion</a>
        </div>
    </aside>
</div>

<?php include APPPATH . 'Views/partials/mobile_money_footer.php'; ?>
