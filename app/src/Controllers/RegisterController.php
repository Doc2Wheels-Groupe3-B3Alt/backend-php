<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class RegisterController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->register();
    }

    private function register()
    {
        $db = (new ConnectDatabase())->execute();
        function RemoveSpecialChar($str)
        {
            $res = preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
            return $res;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['username'])) {
                $message = "Veuillez remplir tous les champs";
                $messageColor = "c-red";
            }
            $username = RemoveSpecialChar($_POST['username']);
            $nom = RemoveSpecialChar($_POST['nom']);
            $prenom = RemoveSpecialChar($_POST['prenom']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $email = ($_POST['email']);

            $testUniqueUsername = $db->query("SELECT * FROM Utilisateurs WHERE username='$username'");
            $resultUsername = $testUniqueUsername->rowCount();

            $testUniqueEmail = $db->query("SELECT * FROM Utilisateurs WHERE email='$email'");
            $resultEmail = $testUniqueEmail->rowCount();

            $stmt = $db->prepare("INSERT INTO Utilisateurs (username, password, email, nom, prenom) VALUES (:username, :password, :email, :nom, :prenom)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);

            $checkPasswordNmbChar = "check-password-false";
            if (strlen($_POST['password']) >= 8) {
                $checkPasswordNmbChar = "check-password-true";
            } else

            if (!$resultUsername == 0) {
                $message = "Cet identifiant est déjà utilisé";
                $messageColor = "c-red";
            } elseif (!$resultEmail == 0) {
                $message = "Cet email est déjà utilisé";
                $messageColor = "c-red";
            } else {
                if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
                    $message = "Veuillez remplir tous les champs";
                    $messageColor = "c-red";
                } else {
                    $stmt->execute();
                    $message = "Inscription réussie";
                    $messageColor = "c-green";
                }
            }
        }

        return new Response('register', 200, get_defined_vars());
    }
}
