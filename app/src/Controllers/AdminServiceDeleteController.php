<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminServiceDeleteController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->deleteService();
    }

    public function deleteService(): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int) $_POST['id'];

            $db = (new ConnectDatabase())->execute();

            // Vérifie si l'utilisateur existe avant de le supprimer
            $stmt = $db->prepare("SELECT * FROM Services WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $service = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($service) {
                $stmt = $db->prepare("DELETE FROM Services WHERE id = :id");
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        return $this->redirect('/admin/services');
    }
}
