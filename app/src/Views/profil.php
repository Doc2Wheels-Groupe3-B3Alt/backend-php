<section class="page-form">
    <h1>Mon Profil</h1>

    <div class="form-bloc mb-8">
        <p>Pseudonyme : <?= htmlspecialchars($user['username']) ?></p>
        <p>Nom : <?= htmlspecialchars($user['nom']) ?></p>
        <p>Prénom : <?= htmlspecialchars($user['prenom']) ?></p>
        <p>Email : <?= htmlspecialchars($user['email']) ?></p>
        <?php if ($user['adresse_id']): ?>
            <hr class="w-8">
            <p>Adresse : <?= htmlspecialchars($user['adresse']) ?></p>
            <p>Ville : <?= htmlspecialchars($user['ville']) ?></p>
            <p>Code postal : <?= htmlspecialchars($user['codepostal']) ?></p>
        <?php endif; ?>
        <?php if ($isTechnicien): ?>
            <hr class="w-8">
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