<section class="page-connexion">
    <h1>Mon Profil</h1>

    <div class="connexion-bloc mb-8">
        <p>Nom : <?= htmlspecialchars($user['nom']) ?></p>
        <p>Prénom : <?= htmlspecialchars($user['prenom']) ?></p>
        <p>Email : <?= htmlspecialchars($user['email']) ?></p>

        <?php if ($isTechnicien): ?>
            <p>Statut : Technicien</p>
            <p>Horaire : <?= $user['heure_debut'] ?> - <?= $user['heure_fin'] ?></p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="/profil/edit" class="button">Modifier mes informations</a>

        <?php if (!$isTechnicien): ?>
            <a href="/profil/technicien" class="button ">Devenir technicien</a>
        <?php endif; ?>
    </div>
</section>