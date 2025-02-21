<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminServicesController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminServices();
    }

    private function adminServices()
    {
        $db = (new ConnectDatabase())->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $stmt = $db->prepare("DELETE FROM Services WHERE id = :id");
            $stmt->execute([':id' => $_POST['delete_id']]);
        }

        $services = $db->query("
            SELECT 
                id,
                nom,
                description
            FROM Services
            ORDER BY id DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminServices', get_defined_vars());
        }
    }
}
