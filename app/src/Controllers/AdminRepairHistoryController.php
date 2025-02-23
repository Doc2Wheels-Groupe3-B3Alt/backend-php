<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminRepairHistoryController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        $db = (new ConnectDatabase())->execute();

        

        $demandes = $db->query("
            SELECT d.*, 
                m.marque, m.modele, m.type, 
                l.adresse AS localisation_adresse, l.ville AS localisation_ville, l.codePostal AS localisation_code_postal,
                s.nom AS service_nom, s.description AS service_description,
                u.nom AS user_nom, u.prenom AS user_prenom
            FROM Demandes d
            LEFT JOIN Modeles m ON d.modele_id = m.id
            LEFT JOIN Localisations l ON d.localisation_id = l.id
            LEFT JOIN Services s ON d.services_id = s.id
            LEFT JOIN Utilisateurs u ON d.user_id = u.id
            WHERE d.statut = 'terminé'
            OR d.statut = 'annulé'
            ORDER BY id DESC;
        ")->fetchAll(\PDO::FETCH_ASSOC);
        

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairHistory', get_defined_vars());
        }
    }
}
