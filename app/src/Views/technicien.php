<div class="homepage-services">
    <?php if (!empty($demandes)): ?>
        <?php foreach ($demandes as $demande): ?>
            <div class="homepage-service bg-light-grey ">
                <h2>Service : <?= htmlspecialchars($demande['service_nom']) ?></h2>
                <p><strong>Statut :</strong> <?= htmlspecialchars($demande['statut']) ?></p>
                <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($demande['date_debut'])) ?> à <?= date('H:i', strtotime($demande['date_debut'])) ?></p>
                <p><strong>Véhicule :</strong> <?= htmlspecialchars($demande['marque']) ?> <?= htmlspecialchars($demande['modele']) ?> (<?= htmlspecialchars($demande['type']) ?>)</p>
                <p><strong>Localisation :</strong> 
                    <?php if (!empty($demande['localisation_adresse'])): ?>
                        <br>
                        <?= htmlspecialchars($demande['localisation_adresse']) ?>,<br>
                        <?= htmlspecialchars($demande['localisation_ville']) ?> (<?= htmlspecialchars($demande['localisation_code_postal']) ?>)
                    <?php else: ?>
                        <em>Adresse non renseignée</em>
                    <?php endif; ?>
                </p>
                <a href="/technicien/edit?id=<?= $demande['id'] ?>" class="button">Modifier</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune demande enregistrée.</p>
    <?php endif; ?>
</div>
