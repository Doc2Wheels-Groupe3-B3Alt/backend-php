<div class="homepage-services">
    <?php if (!empty($demandes)): ?>
        <?php foreach ($demandes as $demande): ?>
            <div class="homepage-service bg-light-grey">
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
                <a href="/profil/demande/avis?demande_id=<?= $demande['id'] ?>" class="button">Avis ou Reclamation</a>
                <script src="https://js.stripe.com/v3/"></script>
                <button id="checkout-button" class="button bg-blue">Payer</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune demande enregistrée.</p>
    <?php endif; ?>
</div>

<script>
    document.getElementById("checkout-button").addEventListener("click", async () => {
        console.log("Bouton cliqué !");

        let response = await fetch("/profil/demandes", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "pay"
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Erreur du serveur :", errorText);
            return;
        }

        const result = await response.json();
        console.log("Réponse du serveur :", result);

        const stripe = Stripe("pk_test_51QukgX4GYCGhgx29uqJfWN5yxkKU8HkMkng3bs6p2Sq98HXcgturCneYuJPbwTKUZJAjjaCCsaCVsZ6CjCK2bkau002WuXklq0");
        const {
            error
        } = await stripe.redirectToCheckout({
            sessionId: result.id
        });

        if (error) {
            console.error("Erreur lors de la redirection vers Stripe :", error);
        }
    });
</script>