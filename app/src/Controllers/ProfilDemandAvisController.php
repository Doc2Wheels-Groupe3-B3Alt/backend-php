<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;
use App\Entities\ReclamationEntity;

class ProfilDemandAvisController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user'])) {
            return $this->redirect('/login');
        }

        return $this->handleAvisReclamation($request);
    }

    public function handleAvisReclamation(Request $request): Response
    {
        $userId = $_SESSION['user']['id'];
        $demandeId = isset($_POST['demande_id']) ? (int)$_POST['demande_id'] : (isset($_GET['demande_id']) ? (int)$_GET['demande_id'] : null);

        $reclamationEntity = new ReclamationEntity();
        $demande = $reclamationEntity->getDemandeById($demandeId, $userId);
        $existingAvis = $reclamationEntity->getExistingAvis($demandeId, $userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $type = $_POST['type'];
                $description = trim($_POST['description']);
                $note = ($type === 'avis') ? (int)$_POST['note'] : null;

                $reclamationEntity->insertReclamation($type, $description, $note, $userId, $demandeId);

                $_SESSION['flash'] = "Votre " . ($type === 'avis' ? "avis" : "réclamation") . " a été enregistré avec succès.";
                return $this->redirect('/profil/demandes');
            } catch (\PDOException $e) {
                $_SESSION['flash'] = "Une erreur est survenue lors de l'enregistrement.";
                error_log("Erreur lors de l'insertion d'un avis/réclamation: " . $e->getMessage());
            }
        }

        return $this->render('profilDemandAvis', get_defined_vars());
    }
}
