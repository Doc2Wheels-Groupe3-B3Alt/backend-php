<section class="page-form">
    <h1>Modifier mes informations</h1>

    <form method="POST" class="form-bloc">
        <div>
            <label>Nom :</label>
            <input class="input mt-4" type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>">
        </div>

        <div>
            <label>Prénom :</label>
            <input class="input mt-4" type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">
        </div>

        <div>
            <label>Email :</label>
            <input class="input mt-4" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <?php if ($user['adresse_id']): ?>
            <div>
                <label>Adresse :</label>
                <input class="input mt-4" type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>">
            </div>

            <div>
                <label>Ville :</label>
                <input class="input mt-4" type="text" name="ville" value="<?= htmlspecialchars($user['ville']) ?>">
            </div>

            <div>
                <label>Code postal :</label>
                <input class="input mt-4" type="text" name="codepostal" value="<?= htmlspecialchars($user['codepostal']) ?>">
            </div>
        <?php endif; ?>

        <?php if (!$user['adresse_id']): ?>
            <div>
                <label>Adresse :</label>
                <input class="input mt-4" type="text" name="adresse">
            </div>

            <div>
                <label>Ville :</label>
                <input class="input mt-4" type="text" name="ville">
            </div>

            <div>
                <label>Code postal :</label>
                <input class="input mt-4" type="text" name="codepostal">
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['user']['role'] === 'technicien') : ?>
            <div class="mt-8">
                <h2 class="text-xl mb-4">Paramètres technicien</h2>

                <div class="form-group">
                    <label>Horaire de travail :</label>
                    <select name="horaire_id" class="input">
                        <?php foreach ($horaires as $horaire) : ?>
                            <option value="<?= $horaire['id'] ?>" <?= $horaire['id'] == $user['horaire_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($horaire['heure_debut'] . ' - ' . $horaire['heure_fin']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" name="revert_to_user" class="button bg-red"
                        onclick="return confirm('Cette action supprimera définitivement vos informations technicien. Continuer ?')">
                        Abandonner le statut technicien
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-actions mt-8">
            <button type="submit" class="button">Enregistrer</button>
            <a href="/profil" class="button button-sm bg-red">Annuler</a>
        </div>
    </form>
</section>