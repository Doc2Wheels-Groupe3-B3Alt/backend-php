<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\Dsn;

class LoginController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->login();
    }

    private function login()
    {

        $dsn = new Dsn();
        $db = new \PDO("mysql:host={$dsn->getHost()};dbname={$dsn->getDbName()};port={$dsn->getPort()}", $dsn->getUser(), $dsn->getPassword());
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $message = "Connexion réussie !";
            } else {
                $message = "Identifiant ou mot de passe incorrect";
            }
        }

        return new Response('login', 200);
    }
}
