<section class="page-connexion">
    <h1>Modifier mes informations</h1>

    <form method="POST" class="connexion-bloc">
        <div class="form-group">
            <label>Nom :</label>
            <input class="input mt-4" type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>">
        </div>

        <div class="form-group">
            <label>Prénom :</label>
            <input class="input mt-4" type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">
        </div>

        <div class="form-group">
            <label>Email :</label>
            <input class="input mt-4" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <button type="submit" class="button mt-4">Enregistrer</button>
        <a href="/profil" class="button button-sm bg-red">Annuler</a>
    </form>
</section>