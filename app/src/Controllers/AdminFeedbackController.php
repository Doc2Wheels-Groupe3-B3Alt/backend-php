<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class AdminFeedbackController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return $this->redirect('/homepage');
        }

        if ($request->getMethod() === 'POST') {
            return $this->handlePostRequest($request);
        }

        return $this->displayReclamations($request);
    }

    private function displayReclamations(Request $request)
    {
        $db = (new ConnectDatabase())->execute();

        // Filtres
        $type = $request->getQueryParam('type', 'all');
        $statut = $request->getQueryParam('statut', 'all');

        // Construction de la requête
        $where = [];
        $params = [];

        if ($type !== 'all') {
            $where[] = "r.type = :type";
            $params[':type'] = $type;
        }

        if ($statut !== 'all') {
            $where[] = "r.statut = :statut";
            $params[':statut'] = $statut;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Récupération des réclamations
        $query = "
            SELECT r.*, 
                   u.nom as user_nom, 
                   u.prenom as user_prenom,
                   d.id as demande_id,
                   d.date_debut as demande_date
            FROM Reclamations r
            LEFT JOIN Utilisateurs u ON r.user_id = u.id
            LEFT JOIN Demandes d ON r.demande_id = d.id
            $whereClause
            ORDER BY r.date_creation DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $reclamations = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Statistiques
        $stats = $this->getStats($db);

        $filters = [
            'type' => $type,
            'statut' => $statut
        ];

        return $this->render('adminFeedback', get_defined_vars());
    }

    private function handlePostRequest(Request $request)
    {
        $db = (new ConnectDatabase())->execute();

        // Décodage du payload JSON
        // $postData = json_decode($request->getPayload(), true);

        // if (!$postData) {
        // Si le payload n'est pas du JSON, utiliser $_POST
        //     $postData = $_POST;
        // }

        $action = $_POST['action'] ?? null;
        $reclamationId = $_POST['reclamation_id'] ?? null;

        if (!$action || !$reclamationId) {
            return $this->redirect('/admin/feedback');
        }

        switch ($action) {
            case 'repondre':
                $reponse = $_POST['reponse'] ?? '';
                if (!empty($reponse)) {
                    $stmt = $db->prepare("
                        UPDATE Reclamations 
                        SET reponse = :reponse,
                            date_reponse = CURRENT_TIMESTAMP,
                            statut = 'resolue',
                            moderateur_id = :moderateur_id
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        ':reponse' => $reponse,
                        ':moderateur_id' => $_SESSION['user']['id'],
                        ':id' => $reclamationId
                    ]);
                }
                break;

            case 'rejeter':
                $stmt = $db->prepare("
                    UPDATE Reclamations 
                    SET statut = 'rejetee',
                        moderateur_id = :moderateur_id
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':moderateur_id' => $_SESSION['user']['id'],
                    ':id' => $reclamationId
                ]);
                break;
            case 'accepter':
                $stmt = $db->prepare("
                        UPDATE Reclamations 
                        SET statut = 'acceptee',
                            moderateur_id = :moderateur_id
                        WHERE id = :id
                    ");
                $stmt->execute([
                    ':moderateur_id' => $_SESSION['user']['id'],
                    ':id' => $reclamationId
                ]);
                break;
        }

        return $this->redirect('/admin/feedback');
    }

    private function getStats($db)
    {
        $stmt = $db->query("
            SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN type = 'avis' THEN 1 END) as total_avis,
                COUNT(CASE WHEN type = 'reclamation' THEN 1 END) as total_reclamations,
                COUNT(CASE WHEN statut = 'en_attente' THEN 1 END) as en_attente,
                AVG(CASE WHEN type = 'avis' THEN note END) as moyenne_avis
            FROM Reclamations
        ");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
