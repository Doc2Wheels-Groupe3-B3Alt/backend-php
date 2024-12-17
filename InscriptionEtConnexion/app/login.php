<form method="post">
    <div id="titre">
        <h1>
            Connexion au compte
        </h1>
    </div>

    <hr>

    <div id="username">
        Identifiant : &nbsp; <input style="width: 30%;" type="text" name="username" rows="1">
    </div>

    <br>

    <div id="password">
        Mot de passe : &nbsp; <input type="password" style="width: 30%;" name="password" rows="1">
    </div>

    <hr>


    <!-- <div id="question">
        Questions secrètes :
        <br>
        <select size="3" multiple name="question">
            <option value="daronne">Quel est le nom de jeune fille de votre mère ?</option>
            <option value="animal">Comment s'appelait votre premier animal de compagnie ?</option>
            <option value="frere">Combien avez-vous de frères et sœurs ?</option>

        </select>
    </div>

    <br>

    <div id="reponse">
        Réponse : &nbsp; <input style="width: 40%;" type="text" name="reponse" rows="1">
    </div>

    <br>
    <hr>
    <div id="num">
        Numéro de téléphone : &nbsp; <input style="width: 40%;" minlength="1" maxlength="10" type="text" name="num" rows="1">
    </div>
    
    <br>
    <hr>

    <div id="secret">
        Code Secret : &nbsp; <input style="width: 40%;" type="text" name="secret" rows="1">
    </div>

    <br>
    <br> -->
    <button type="submit">Se connecter</button>

    <!-- <div id="valider">
        <input type="submit" value="envoyer" />
    </div> -->

</form>

<?php
$conn = new PDO("pgsql:host=db;port=5432;dbname=projet", "admin", "admin");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo "Connexion réussie !";
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>