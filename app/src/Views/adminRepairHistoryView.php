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
        <br>
        <p>Intervenant : <?= htmlspecialchars($intervenant['nom']) ?> <?= htmlspecialchars($intervenant['prenom']) ?></p>
    </div>

</section>