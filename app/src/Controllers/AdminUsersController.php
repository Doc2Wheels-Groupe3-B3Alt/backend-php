<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminUsersController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminUser();
    }

    private function adminUser()
    {
        $db = (new ConnectDatabase())->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $stmt = $db->prepare("DELETE FROM Utilisateurs WHERE id = :id");
            $stmt->execute([':id' => $_POST['delete_id']]);
        }

        $users = $db->query("
            SELECT 
                id,
                username,
                email,
                nom,
                prenom,
                admin,
                TO_CHAR(date_creation, 'DD/MM/YYYY HH24:MI') as date_creation
            FROM Utilisateurs
            ORDER BY date_creation DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminUtilisateurs', get_defined_vars());
        }
    }
}
