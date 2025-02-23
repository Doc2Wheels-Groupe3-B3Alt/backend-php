<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminRepairHistoryViewController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        $db = (new ConnectDatabase())->execute();

       

        $stmt = $db->prepare("
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
            AND d.id = :id
            ORDER BY id DESC;
        ");
        $stmt->execute([':id' => $_GET['id']]);
        $demande = $stmt->fetch(\PDO::FETCH_ASSOC);
        
       

        $stmt = $db->prepare("
            SELECT DISTINCT * FROM Utilisateurs WHERE intervenant_id = :id;
        ");
        $stmt->execute([':id' => $demande['intervenant_id']]);
        $intervenant = $stmt->fetch(\PDO::FETCH_ASSOC);


        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairHistoryView', get_defined_vars());
        }
    }
}
