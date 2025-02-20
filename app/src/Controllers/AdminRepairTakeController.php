<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminRepairTakeController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->adminRepair();
    }

    private function adminRepair()
    {
        $db = (new ConnectDatabase())->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['take_id'])) {
            
            
                $stmt = $db->prepare("UPDATE Demandes SET statut = 'En cours', intervenant_id = :intervenant_id WHERE id = :id");
                $stmt->execute([':id' => $_POST['take_id'], ':intervenant_id' => $_POST['technicien_id']]);
                return $this->redirect('/admin/repair/take?id=' . $_POST['take_id']);
            
        }

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
            WHERE d.statut = 'En attente'
            AND d.id = :id
            ORDER BY id DESC;
        ");
        $stmt->execute([':id' => $_GET['id']]);
        $demande = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $stmt = $db->prepare("
            SELECT u.* FROM Utilisateurs u
            LEFT JOIN Adresses a ON u.adresse_id = a.id
            WHERE u.role = 'technicien'
            AND a.ville = :ville;
            ;
        ");

        $stmt->execute([':ville' => $demande['localisation_ville']]);
        $techniciens = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        } else {
            return $this->render('adminRepairTake', get_defined_vars());
        }
    }
}
