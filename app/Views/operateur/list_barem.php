<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barème des Frais</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2 class="mb-4">Barème des Frais par Tranche de Montant</h2>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Type d'Opération</th>
                <th>Intervalle (Tranche de Montant)</th>
                <th>Frais Appliqués</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($baremes)): ?>
                <?php foreach ($baremes as $b): ?>
                    <tr>
                        <td>
                            <span class="badge bg-primary">
                                <?= esc(ucfirst($b['type_operation_nom'])) ?>
                            </span>
                        </td>
                        <td>
                            Entre <strong><?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar</strong> 
                            et <strong><?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</strong>
                        </td>
                        <td>
                            <strong class="text-danger"><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Aucun barème configuré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>