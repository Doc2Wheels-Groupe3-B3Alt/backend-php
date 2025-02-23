<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\User;

class AdminUserDeleteController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->deleteUser();
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
            $tmp = new User();
            $user = $tmp->getUserById($id);

            if ($user) {
                $tmp->deleteById($id);
            }
        }

        return $this->redirect('/admin/utilisateurs');
    }
}
