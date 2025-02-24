<section class="table-page">
    <h1><?= isset($service) ? 'Modifier' : 'Créer' ?> un service</h1>

    <form action="/admin/services/edit" method="POST" class="form-bloc">
        <input type="hidden" name="id" value="<?= isset($service) ? $service['id'] : '' ?>">

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= isset($service) ? $service['nom'] : '' ?>" required>

        <label for="description">Description :</label>
        <input type="text" name="description" id="description" value="<?= isset($service) ? $service['description'] : '' ?>" required>

        <button type="submit" class="button"><?= isset($service) ? 'Modifier' : 'Créer' ?></button>

        <a href="/admin/services">Retour à la liste</a>
    </form>
</section>