<?php

namespace App\Entities;

use App\Commands\ConnectDatabase;

class ReclamationEntity
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnectDatabase())->execute();
    }

    public function getReclamations($type, $statut)
    {
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

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare("
            SELECT r.*, u.prenom as user_prenom, u.nom as user_nom
            FROM Reclamations r
            JOIN Utilisateurs u ON r.user_id = u.id
            $whereSql
        ");
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateReclamation($action, $reclamationId, $reponse = null)
    {
        switch ($action) {
            case 'repondre':
                if (!empty($reponse)) {
                    $stmt = $this->db->prepare("
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
                $stmt = $this->db->prepare("
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
                $stmt = $this->db->prepare("
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
    }

    public function getStats()
    {
        $stmt = $this->db->query("
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

    public function getDemandeById($demandeId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT d.* 
            FROM Demandes d 
            WHERE d.id = :demande_id 
            AND d.user_id = :user_id 
            AND d.date_fin IS NOT NULL
        ");
        $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getExistingAvis($demandeId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM Reclamations 
            WHERE demande_id = :demande_id 
            AND user_id = :user_id
        ");
        $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function insertReclamation($type, $description, $note, $userId, $demandeId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO Reclamations (type, description, note, user_id, demande_id, date_creation)
            VALUES (:type, :description, :note, :user_id, :demande_id, CURRENT_TIMESTAMP)
        ");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':note', $note, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':demande_id', $demandeId, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getReclamationsByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT demande_id
            FROM Reclamations
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
