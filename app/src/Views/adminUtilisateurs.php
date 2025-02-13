<section class="table-page">
    <h1>Gestion des Utilisateurs</h1>

    <a href="/admin/utilisateurs/edit" class="button button-sm bg-green">Créer un utilisateur</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudonyme</th>
                <th>Email</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôles</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['admin']) ?></td>
                    <td><?= htmlspecialchars($user['date_creation']) ?></td>
                    <td class="actions">
                        <a href="/admin/utilisateurs/edit?id=<?= $user['id'] ?>" class="button button-sm bg-blue c-white">Modifier</a>
                        <form action="/admin/utilisateurs/delete" method="POST">
                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')" class="button button-sm bg-red c-white">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>