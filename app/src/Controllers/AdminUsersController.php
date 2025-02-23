<?php

namespace App\Controllers;

use App\Entities\User;
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

        $tmp = new User();
        $users = $tmp->getUsers();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminUtilisateurs', get_defined_vars());
        }
    }
}
