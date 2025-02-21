<section class="table-page">
    <h1>Gestion des Services</h1>

    <a href="/admin/services/edit" class="button button-sm bg-green">Créer un service</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= htmlspecialchars($service['id']) ?></td>
                    <td><?= htmlspecialchars($service['nom']) ?></td>
                    <td><?= htmlspecialchars($service['description']) ?></td>
                    <td class="actions">
                        <a href="/admin/services/edit?id=<?= $service['id'] ?>" class="button button-sm bg-blue c-white">Modifier</a>
                        <form action="/admin/services/delete" method="POST">
                            <input type="hidden" name="id" value="<?= $service['id']; ?>">
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')" class="button button-sm bg-red c-white">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>