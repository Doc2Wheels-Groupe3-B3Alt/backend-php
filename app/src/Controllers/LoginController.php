<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class LoginController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if ($this->isLoggedIn()) {
            return $this->redirect('/homepage');
        }

        return $this->login();
    }

    private function login()
    {
        $db = (new ConnectDatabase())->execute();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            $email = $db->query("SELECT email FROM Utilisateurs WHERE email='$username'")->fetchColumn();

            if ($email || $user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'admin' => (bool)$user['admin']
                ];
                return $this->redirect('/homepage');

                $message = "Connexion réussie";
                $messageColor = "c-green";
            } else {
                $message = "Identifiant ou mot de passe incorrect";
                $messageColor = "c-red";
            }
        }

        return $this->render('login', get_defined_vars());
    }
}
