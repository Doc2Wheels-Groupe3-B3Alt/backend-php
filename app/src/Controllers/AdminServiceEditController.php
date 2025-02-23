<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminServiceEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->editService($request);
    }

    public function editService(Request $request): Response
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        $db = (new ConnectDatabase())->execute();
        $service = null;

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $db->prepare("SELECT * FROM Services WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $service = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);

            if ($id) {
                $stmt = $db->prepare("UPDATE Services SET nom = :nom, description = :description WHERE id = :id");
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("INSERT INTO Services (nom, description) VALUES (:nom, :description)");
            }

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            return $this->redirect('/admin/services');
        }

        return $this->render('adminServiceEdit', get_defined_vars());
    }
}
