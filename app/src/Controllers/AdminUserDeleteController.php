<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminUserDeleteController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->deleteUser();
        }
    }

    public function deleteUser(): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int) $_POST['id'];

            $db = (new ConnectDatabase())->execute();

            // Vérifie si l'utilisateur existe avant de le supprimer
            $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                $stmt = $db->prepare("DELETE FROM Utilisateurs WHERE id = :id");
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        return $this->redirect('/admin/utilisateurs');
    }
}
