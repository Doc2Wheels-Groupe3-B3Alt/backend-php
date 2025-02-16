<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class RegisterController extends AbstractController
{
    public function process(Request $request): Response
    {
        session_start();

        if ($this->isLoggedIn()) {
            return $this->redirect('/homepage');
        }

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

            if (strlen($_POST['password']) >= 8) {
                $checkPasswordNmbChar = "check-password-true";
            } else {
                $checkPasswordNmbChar = "check-password-false";
            }

            if (!$resultUsername == 0) {
                $message = "Cet identifiant est déjà utilisé";
                $messageColor = "c-red";
            } elseif (!$resultEmail == 0) {
                $message = "Cet email est déjà utilisé";
                $messageColor = "c-red";
            } elseif (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
                $message = "Veuillez remplir tous les champs";
                $messageColor = "c-red";
            } elseif (strlen($_POST['password']) < 8) {
                $message = "Le mot de passe doit comporter au moins 8 caractères";
                $messageColor = "c-red";
            } else {
                $stmt->execute();
                $message = "Inscription réussie";
                $messageColor = "c-green";

                $_SESSION['user'] = [
                    'id' => $db->lastInsertId(),
                    'username' => $username,
                    'admin' => false
                ];

                return $this->redirect('/homepage');
            }
        }

        return $this->render('register', get_defined_vars());
    }
}
