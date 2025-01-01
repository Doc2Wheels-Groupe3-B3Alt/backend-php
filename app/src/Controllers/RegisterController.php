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

        $dsn = new Dsn();
        // Ajout de "dbname={$dsn->getDbName()};" pour se connecter à la base de données
        $db = new \PDO("mysql:host={$dsn->getHost()};dbname={$dsn->getDbName()};port={$dsn->getPort()}", $dsn->getUser(), $dsn->getPassword());

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
        
            if ($stmt->execute()) {
                echo "Inscription réussie !";
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
        
        ob_start();
        include __DIR__ . '/../Views/register.php';
        $content = ob_get_clean();

        return new Response($content, 200);
    }
}
