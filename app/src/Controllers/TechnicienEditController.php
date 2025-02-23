<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class TechnicienEditController extends AbstractController
{
    public function process(Request $request): Response
    {
       

        $this->startSessionIfNeeded();
        $db = (new ConnectDatabase())->execute();

        if (!isset($_SESSION['user'] )) {
            return $this->redirect('/login');
        }

        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'technicien') {
            return $this->redirect('/homepage');
        }

        $stmt = $db->prepare("SELECT intervenant_id FROM Utilisateurs WHERE id = :user_id");
        $stmt->execute([':user_id' => $_SESSION['user']['id']]);
        $intervenant = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$intervenant) {
            return $this->redirect('/error');
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['statut'] ?? null;
            
        
            if (!in_array($statut, ['terminé', 'annulé'])) {
                return $this->redirect('/error');
            }
        
            $stmt = $db->prepare("
                UPDATE Demandes SET
                    statut = :statut,
                    date_fin = CURRENT_TIMESTAMP
                WHERE intervenant_id = :intervenant_id
                AND id = :id
            ");
            $stmt->execute([
                ':statut' => $statut,
                ':intervenant_id' => $intervenant['intervenant_id'],
                ':id' => $_POST['id']
            ]);

        
            return $this->redirect('/technicien');
        }
        
        

        $stmt = $db->prepare("
            SELECT d.*, 
                m.marque, m.modele, m.type, 
                l.adresse AS localisation_adresse, l.ville AS localisation_ville, l.codePostal AS localisation_code_postal,
                s.nom AS service_nom, s.description AS service_description
            FROM Demandes d
            LEFT JOIN Modeles m ON d.modele_id = m.id
            LEFT JOIN Localisations l ON d.localisation_id = l.id
            LEFT JOIN Services s ON d.services_id = s.id
            WHERE d.intervenant_id = :intervenant_id
            AND d.id = :id;
        ");
        $stmt->execute([':intervenant_id' => $intervenant['intervenant_id'], ':id' => $_GET['id']]);
        
        $demandes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($demandes == null) {
            return $this->redirect('/homepage');
        }
    
        

        return $this->render('technicienEdit', get_defined_vars());
    }
}
