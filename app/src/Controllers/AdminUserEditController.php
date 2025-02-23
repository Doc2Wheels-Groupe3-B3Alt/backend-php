<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\User;

class AdminUserEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        return $this->editUser($request);
    }

    public function editUser(Request $request): Response
    {
        $this->startSessionIfNeeded();

        
        $user = null;

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $tmp = new User();
            $user = $tmp->getUserById($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $role = $_POST['role'];

            if ($id) {
                if ($password) {
                    $tmp = new User();
                    $user = $tmp->updatePassword($id, $prenom, $nom, $username, $email, $password, $role);
                } else {
                    $tmp = new User();
                    $user = $tmp->updateWithoutPassword($id, $prenom, $nom, $username, $email, $role);
                }
               
            } else {
                $tmp = new User();
                $user = $tmp->insertUser($prenom, $nom, $username, $email, $password, $role);
            }

        
            return $this->redirect('/admin/utilisateurs');
        }

        return $this->render('adminUserEdit', get_defined_vars());
    }
}
