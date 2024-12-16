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
<form method="post">
    Nom d'utilisateur: <input type="text" name="username"><br>
    Mot de passe: <input type="password" name="password"><br>
    <button type="submit">Se connecter</button>
</form>