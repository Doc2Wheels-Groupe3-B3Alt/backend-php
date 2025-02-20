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

            $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE username = :username OR email = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user['is_verified']) {
                $message = "Veuillez vérifier votre email avant de vous connecter";
                $messageColor = "c-red";
                return $this->render('login', get_defined_vars());
            }
            if ($user && password_verify($password, $user['password'])) {
                $message = "Connexion réussie";
                $messageColor = "c-green";

                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    
                ];
                return $this->redirect('/homepage');
            } else {
                $message = "Identifiant ou mot de passe incorrect";
                $messageColor = "c-red";
            }
        }

        return $this->render('login', get_defined_vars());
    }
}
