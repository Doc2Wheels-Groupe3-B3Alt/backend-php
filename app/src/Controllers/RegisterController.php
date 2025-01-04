<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\Dsn;

class RegisterController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->register();
    }

    private function register()
    {
        // error_reporting(0);

        $dsn = new Dsn();
        // Ajout de "dbname={$dsn->getDbName()};" pour se connecter à la base de données
        $db = new \PDO("mysql:host={$dsn->getHost()};dbname={$dsn->getDbName()};port={$dsn->getPort()}", $dsn->getUser(), $dsn->getPassword());

        function RemoveSpecialChar($str)
        {
            $res = preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
            return $res;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = RemoveSpecialChar($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $test = $db->query("SELECT * FROM users WHERE username='$username'");
            $result = $test->rowCount();

            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            // if ($db->query("SELECT * FROM users WHERE username='$username'")) {
            //     echo "Cet identifiant est déjà utilisé";
            // }

            // Régler erreur "Undefined variable: message"
            if ($result == 0) {
                $stmt->execute();
                $message = "Inscription réussie";
            } else {
                $message = "Cet identifiant est déjà utilisé";
            }
        }

        return new Response('register', 200);
    }
}
