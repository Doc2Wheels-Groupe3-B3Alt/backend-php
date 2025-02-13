<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminUserEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] !== 'admin') {
            return $this->redirect('/homepage');
        }
        if ($request->getMethod() === 'POST') {
            return $this->saveUser($request);
        }
        return $this->editUser($request);
    }

    private function editUser(Request $request): Response
    {
        $db = (new ConnectDatabase())->execute();
        $user = null;

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return $this->render('adminUserEdit', get_defined_vars());
    }

    public function saveUser(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new ConnectDatabase())->execute();

            $id = $_POST['id'] ?? null;
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $admin = isset($_POST['admin']) ? 'admin' : 'user';

            if ($id) {
                if ($password) {
                    $stmt = $db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, password = :password, admin = :admin WHERE id = :id");
                    $stmt->bindParam(':password', $password);
                } else {
                    $stmt = $db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, admin = :admin WHERE id = :id");
                }
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("INSERT INTO Utilisateurs (prenom, nom, username, email, password, admin) VALUES (:prenom, :nom, :username, :email, :password, :admin)");
                $stmt->bindParam(':password', $password);
            }

            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':admin', $admin);
            $stmt->execute();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->redirect('/admin/utilisateurs');
        }
    }
}
