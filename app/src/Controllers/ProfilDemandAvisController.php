<?php
// ProfilDemandAvisController.php
namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

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
        $db = (new ConnectDatabase())->execute();
        $userId = $_SESSION['user']['id'];

        $demandeId = isset($_POST['demande_id']) ? (int)$_POST['demande_id'] : (isset($_GET['demande_id']) ? (int)$_GET['demande_id'] : null);

        $stmt = $db->prepare("
            SELECT d.* 
            FROM Demandes d 
            WHERE d.id = :demande_id 
            AND d.user_id = :user_id 
            AND d.date_fin IS NOT NULL
        ");
        $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $demande = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT * FROM Reclamations 
            WHERE demande_id = :demande_id 
            AND user_id = :user_id
        ");
        $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $existingAvis = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $type = $_POST['type'];
                $description = trim($_POST['description']);
                $note = ($type === 'avis') ? (int)$_POST['note'] : null;

                $stmt = $db->prepare("
                    INSERT INTO Reclamations (type, description, note, user_id, demande_id, date_creation)
                    VALUES (:type, :description, :note, :user_id, :demande_id, CURRENT_TIMESTAMP)
                ");

                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':note', $note, \PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
                $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);

                $stmt->execute();

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
