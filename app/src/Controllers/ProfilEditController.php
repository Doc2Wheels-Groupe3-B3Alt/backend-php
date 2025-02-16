<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class EditProfilController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->checkAuth();
        $db = (new ConnectDatabase())->execute();

        if ($request->getMethod() === 'POST') {
            $this->updateUser($db, $request->getPayload());
            return new Response('', 302, ['Location' => '/profil']);
        }

        return $this->showForm($db);
    }

    private function showForm(\PDO $db): Response
    {
        $user = $db->query("
            SELECT * FROM Utilisateurs 
            WHERE id = " . $_SESSION['user']['id']
        )->fetch();

        return $this->render('edit_profil', ['user' => $user]);
    }

    private function updateUser(\PDO $db, array $data): void
    {
        $stmt = $db->prepare("
            UPDATE Utilisateurs SET
                nom = :nom,
                prenom = :prenom,
                email = :email
            WHERE id = :id
        ");
        
        $stmt->execute([
            ':nom' => htmlspecialchars($data['nom']),
            ':prenom' => htmlspecialchars($data['prenom']),
            ':email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            ':id' => $_SESSION['user']['id']
        ]);
    }
}