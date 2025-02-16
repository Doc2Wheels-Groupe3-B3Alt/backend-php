<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class ProfilController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->checkAuth();
        $db = (new ConnectDatabase())->execute();

        $stmt = $db->prepare("
            SELECT u.*, a.*, h.heure_debut, h.heure_fin 
            FROM Utilisateurs u
            LEFT JOIN Adresses a ON u.adresse_id = a.id
            LEFT JOIN Intervenants i ON u.intervenant_id = i.id
            LEFT JOIN HorairesTravail h ON i.horaire_id = h.id
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $_SESSION['user']['id']]);
        $user = $stmt->fetch();
        $isTechnicien = $_SESSION['user']['role'] === 'technicien';

        return $this->render('profil', get_defined_vars());
    }
}
