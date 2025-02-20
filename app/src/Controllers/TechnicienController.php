<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class TechnicienController extends AbstractController
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

        $_SESSION['user']['intervenant_id'] = $intervenant['intervenant_id'];

        $stmt = $db->prepare("
            SELECT d.*, 
                m.marque, m.modele, m.type, 
                l.adresse AS localisation_adresse, l.ville AS localisation_ville, l.codePostal AS localisation_code_postal,
                s.nom AS service_nom, s.description AS service_description
            FROM Demandes d
            LEFT JOIN Modeles m ON d.modele_id = m.id
            LEFT JOIN Localisations l ON d.localisation_id = l.id
            LEFT JOIN Services s ON d.services_id = s.id
            WHERE d.intervenant_id = :id
            AND d.statut = 'En cours'
        ");
        $stmt->execute([':id' => $_SESSION['user']['intervenant_id']]);
        
        $demandes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        

        return $this->render('technicien', get_defined_vars());
    }
}
