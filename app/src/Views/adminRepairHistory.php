<section class="table-page">
    <h1>Historique de réparation</h1>

   
    <table class="table">
        <thead>
            <tr>
            <th>ID</th>
            <th>Motif</th>
            <th>Type</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Client</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                <td><?= htmlspecialchars($demande['id']) ?></td>
                <td><?= htmlspecialchars($demande['service_nom']) ?></td>
                <td><?= htmlspecialchars($demande['type']) ?></td>
                <td><?= htmlspecialchars($demande['marque']) ?></td>
                <td><?= htmlspecialchars($demande['modele']) ?></td>
                <td><?= htmlspecialchars($demande['user_nom']) ?> <?= htmlspecialchars($demande['user_prenom']) ?></td>
                    
                    <td class="actions">
                        <a href="/admin/repair/history/view?id=<?= $demande['id'] ?>" class="button button-sm bg-blue c-white">Consulter</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>