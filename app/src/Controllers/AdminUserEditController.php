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

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        return $this->editUser($request);
    }

    public function editUser(Request $request): Response
    {
        $this->startSessionIfNeeded();

        $db = (new ConnectDatabase())->execute();
        $user = null;

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new ConnectDatabase())->execute();

            $id = $_POST['id'] ?? null;
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $role = $_POST['role'];

            if ($id) {
                if ($password) {
                    $stmt = $db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, password = :password, role = :role WHERE id = :id");
                    $stmt->bindParam(':password', $password);
                } else {
                    $stmt = $db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, role = :role WHERE id = :id");
                }
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("INSERT INTO Utilisateurs (prenom, nom, username, email, password, role) VALUES (:prenom, :nom, :username, :email, :password, :role)");
                $stmt->bindParam(':password', $password);
            }

            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            return $this->redirect('/admin/utilisateurs');
        }

        return $this->render('adminUserEdit', get_defined_vars());
    }
}
