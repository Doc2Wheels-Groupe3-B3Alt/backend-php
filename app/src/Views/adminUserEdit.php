<section class="table-page">
    <h1><?= isset($user) ? 'Modifier' : 'Créer' ?> un utilisateur</h1>

    <form action="/admin/utilisateurs/edit" method="POST" class="connexion-bloc">
        <input type="hidden" name="id" value="<?= isset($user) ? $user['id'] : '' ?>">

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" value="<?= isset($user) ? $user['prenom'] : '' ?>" required>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= isset($user) ? $user['nom'] : '' ?>" required>

        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" value="<?= isset($user) ? $user['username'] : '' ?>" required>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" value="<?= isset($user) ? $user['email'] : '' ?>" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" <?= isset($user) ? '' : 'required' ?>>

        <label for="role">Rôle :</label>
        <select name="role" id="role" required>
            <option value="user" <?= isset($user) && $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= isset($user) && $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            <option value="technicien" <?= isset($user) && $user['role'] === 'technicien' ? 'selected' : '' ?>>Technicien</option>
        </select>

        <button type="submit" class="button"><?= isset($user) ? 'Modifier' : 'Créer' ?></button>

        <a href="/admin/utilisateurs">Retour à la liste</a>
    </form>
</section>