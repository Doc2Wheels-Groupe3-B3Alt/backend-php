<button class="button" onclick="history.back();">Retour</button>
<section class="page-connexion">
    <h1>Demande de réparation</h1>

    <div class="connexion-bloc mb-8">
        <p>Motif : <?= htmlspecialchars($demande['service_nom']) ?></p>
        <p>ID : <?= htmlspecialchars($demande['id']) ?></p>
        <p>Type : <?= htmlspecialchars($demande['type']) ?></p>
        <p>Marque : <?= htmlspecialchars($demande['marque']) ?></p>
        <p>Modèle : <?= htmlspecialchars($demande['modele']) ?></p>
        <p>Client : <?= htmlspecialchars($demande['user_nom']) ?> <?= htmlspecialchars($demande['user_prenom']) ?></p>
        <p>Adresse : <?= htmlspecialchars($demande['localisation_adresse']) ?></p>
        <p>Ville : <?= htmlspecialchars($demande['localisation_ville']) ?></p>
        <p>Code Postal : <?= htmlspecialchars($demande['localisation_adresse']) ?></p>
        
    </div>

    <div class="actions">
        <form action="/admin/repair/take" method="POST">
            <label for="technicien_id">Technicien</label>
            <select name="technicien_id" id="technicien_id" class="input" required>
                <option value="" selected disabled>--Choisir le technicien--</option>
                <?php foreach ($techniciens as $technicien): ?>
                    <option value="<?= $technicien['intervenant_id'] ?>"><?= $technicien['nom'] ?> <?= $technicien['prenom'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="take_id" value="<?= $demande['id']; ?>">
            <button type="submit" class="button mt-2">Attribuer la réparation</button>
        </form>
    </div>
</section>