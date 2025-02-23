<section class="homepage-section flex-row align-items-center justify-content-center">
    <div>
        <img class="media" src="/src/Images/media.png" alt="Réparation">
    </div>
    <div class="flex-column align-items-center justify-content-space-between gap-8">
        <img class="placeholder" src="/src/Images/Placeholder2.png" alt="Réparation">
        <a href="/" class="button button-lg bg-light-grey c-black homepage-button">Faire un devis</a>
    </div>
</section>
<section class="flex align-items-center justify-content-center homepage-section position-relative bg-light-grey">
    <p class="description-text homepage-title">Description</p>
    <img class="description-placeholder" src="/src/Images/Placeholder2.png" alt="Description">
</section>
<section>
    <h1 class="homepage-title">Nos services</h1>
    <div class="homepage-services">
        <?php foreach ($services as $service): ?>
            <div class="homepage-service bg-light-grey">
                <h2><?= htmlspecialchars($service['nom']) ?></h2>
                <p><?= htmlspecialchars($service['description']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<section class="flex flex-column justify-content-center align-items-center m-8 gap-8">
    <h1 class="homepage-title">Zone d'intervention</h1>
    <img src="/src/Images/Placeholder2.png" alt="Placeholder">
    <img class="map" src="/src/Images/map.png" alt="Carte">
</section>
<section class="flex align-items-center justify-content-center homepage-section position-relative bg-light-grey mb-8">
    <p class="description-text homepage-title">A propos</p>
    <img class="description-placeholder" src="/src/Images/Placeholder2.png" alt="Description">
</section>