<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\Dsn;
use App\Commands\ConnectDatabase;

class LoginController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->login();
    }

    private function login()
    {
        $db = (new ConnectDatabase())->execute();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Régler erreur "Undefined variable: message"
            if ($user && password_verify($password, $user['password'])) {
                $message = "Connexion réussie !";
            } else {
                $message = "Identifiant ou mot de passe incorrect";
            }
        }

        return new Response('login', 200, get_defined_vars());
    }
}
