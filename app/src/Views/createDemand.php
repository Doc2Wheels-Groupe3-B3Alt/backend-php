<section class="page-connexion">


    <form method="POST" action="/createDemand">
        <h1>Créer une demande de réparation</h1>
        <div class="grid ">
            <div class="col-4">
                <h4>Véhicule</h4>
                <div class="mt-4">
                    <label  for="id_service">Type</label>
                    <select class="input" name="typeMoto" id="typeMoto" required>
                        <option value="" selected disabled>--Choisir le type--</option>
                        <option value="scooter">Scooter</option>
                        <option value="roadster">Roadster</option>
                        <option value="sportive">Sportive</option>
                        <option value="trail">Trail</option>
                        <option value="supermotard">Supermotard</option>
                        <option value="enduro">Enduro</option>
                    </select>
                    <div class="mt-4">
                        <label for="marque">Marque</label>
                        <input class="input" type="text" name="marque" id="marque" required>
                    </div>
                    <div class="mt-4">
                        <label for="modele">Modèle</label>
                        <input class="input" type="text" name="modele" id="modele" required>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <h4>Prestation</h4> 
                <div class="mt-4">
                    <label for="date">Date</label>
                    <input class="input" type="date" name="date" id="date" required>
                </div>
                <div class="mt-4">
                    <label for="heure">Heure</label>
                    <input class="input" type="time" name="heure" id="heure" required>
                </div>
                <div class="mt-4">
                    <label for="id_service">Service</label>
                    <select class="input" name="id_service" id="id_service" required>
                        <option value="" selected disabled>--Choisir le service--</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>"><?= $service['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class=" text-center mt-8">
                    <button type="submit" class="button">Envoyer</button>
                </div>
            </div>
            <div class="col-4">
                <h4>Localisation</h4> 
                <div class="mt-4">
                    <label for="adresse">Adresse</label>
                    <input class="input" type="text" name="adresse" id="adresse" required>
                </div>
                <div class="mt-4">
                    <label for="ville">Ville</label>
                    <input class="input" type="text" name="ville" id="ville" required>
                </div>
                <div class="mt-4">
                    <label for="code_postal">Code Postal</label>
                    <input class="input" type="text" name="code_postal" id="code_postal" required>
                </div>
            </div>
            
        </div>
                        </form> 
</section>