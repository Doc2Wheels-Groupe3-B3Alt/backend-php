<section class="page-connexion">
    <h1>Devenir technicien</h1>

    <form method="POST" class="connexion-bloc">
        <h2>Adresse de travail</h2>
        <div class="form-group">
            <label>Adresse :</label>
            <input class="input" type="text" name="adresse" required>
        </div>

        <div class="form-group">
            <label>Ville :</label>
            <input class="input" type="text" name="ville" required>
        </div>

        <div class="form-group">
            <label>Code postal :</label>
            <input class="input" type="text" name="codePostal" required>
        </div>

        <div class="form-group">
            <label>Complément :</label>
            <textarea class="input" name="complement"></textarea>
        </div>

        <h2>Disponibilités</h2>
        <div class="form-group">
            <label>Horaire :</label>
            <select name="horaire_id" required>
                <?php foreach ($horaires as $horaire): ?>
                    <option value="<?= $horaire['id'] ?>">
                        <?= htmlspecialchars($horaire['heure_debut'] . ' - ' . $horaire['heure_fin']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="button mt-8">Valider</button>
        <a href="/profil" class="button button-sm bg-red">Annuler</a>
    </form>
</section>