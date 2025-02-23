<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use \DateTime;

class CreateDemandController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user'])) {
            return $this->redirect('/login');
        }
        return $this->homepage();
    }

    private function homepage()
    {
        $db = (new ConnectDatabase())->execute();

        $services = $db->query("
            SELECT 
                id,
                nom,
                description
            FROM Services
            ORDER BY id DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $typeMoto = $_POST['typeMoto'] ?? '';
            $marque = $_POST['marque'] ?? '';
            $modele = $_POST['modele'] ?? '';

            $date = $_POST['date'] ?? '';
            $heure = $_POST['heure'] ?? '';
            $id_service = $_POST['id_service'] ?? '';

            $dateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $heure);
            $date_debut = $dateTime ? $dateTime->format('Y-m-d H:i:s') : '';

            $adresse = $_POST['adresse'] ?? '';
            $ville = $_POST['ville'] ?? '';
            $code_postal = $_POST['code_postal'] ?? '';
            $user_id = $_SESSION['user']['id'] ?? 0;

            $stmt = $db->prepare("
                INSERT INTO Modeles (type, marque, modele)
                VALUES (:type, :marque, :modele)
            ");
            $stmt->execute([
                ':type' => htmlspecialchars($typeMoto),
                ':marque' => htmlspecialchars($marque),
                ':modele' => htmlspecialchars($modele)
            ]);
            $modelesId = $db->lastInsertId();

            
            $stmt = $db->prepare("
                INSERT INTO Localisations (adresse, ville, codePostal)
                VALUES (:adresse, :ville, :codePostal)
            ");
            $stmt->execute([
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':codePostal' => htmlspecialchars($code_postal)
            ]);
            $localisationId = $db->lastInsertId();

            $stmt = $db->prepare("
                INSERT INTO Demandes (date_debut, user_id, modele_id, localisation_id, services_id)
                VALUES (:date_debut, :user_id, :modele_id, :localisation_id, :services_id)
            ");
            $stmt->execute([
                
                ':date_debut' => htmlspecialchars($date_debut ?? ''),
                ':user_id' => htmlspecialchars($user_id ?? ''),
                ':modele_id' => htmlspecialchars($modelesId ?? ''),
                ':localisation_id' => htmlspecialchars($localisationId ?? ''),
                ':services_id' => htmlspecialchars($id_service ?? '')
            
            ]);
            return $this->redirect('/profil/demandes');
        }

        return $this->render('createDemand', get_defined_vars());
    }
}
