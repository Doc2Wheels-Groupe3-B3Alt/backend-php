<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class ProfilDemandController extends AbstractController
{
    public function process(Request $request): Response
    {
       

        $this->startSessionIfNeeded();
        $db = (new ConnectDatabase())->execute();

        if (!isset($_SESSION['user'])) {
            return $this->redirect('/login');
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
            WHERE d.user_id = :id
        ");
        $stmt->execute([':id' => $_SESSION['user']['id']]);
        
        $demandes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        var_dump($demandes);

        return $this->render('profilDemand', get_defined_vars());
    }
}
