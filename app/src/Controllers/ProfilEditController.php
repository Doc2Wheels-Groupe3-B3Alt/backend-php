<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class ProfilEditController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        return $this->updateUser($request);
    }

    private function updateUser(Request $request): Response
    {
        $db = (new ConnectDatabase())->execute();

        $user = $db->query(
            "
            SELECT * FROM Utilisateurs 
            WHERE id = " . $_SESSION['user']['id']
        )->fetch();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stmt = $db->prepare("
            UPDATE Utilisateurs SET
                nom = :nom,
                prenom = :prenom,
                email = :email
            WHERE id = :id
            ");

            $stmt->execute([
                ':nom' => htmlspecialchars($_POST['nom']),
                ':prenom' => htmlspecialchars($_POST['prenom']),
                ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                ':id' => $_SESSION['user']['id']
            ]);

            $this->redirect('/profil');
        }

        return $this->render('profilEdit', get_defined_vars());
    }
}
