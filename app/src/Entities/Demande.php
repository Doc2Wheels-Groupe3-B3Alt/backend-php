<?php


namespace App\Entities;

use App\Commands\ConnectDatabase;



class Demande extends AbstractEntity
{
   
    private $db;
    public int $id;
    public function __construct() {
        $this->db = (new ConnectDatabase())->execute();
    }
    

    public function getId(): int
    {
        return $this->id;
    }

   
    public function getHistoryDemandeById(int $id) {
        $stmt = $this->db->prepare("
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
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);;
    }

    public function getHistoriesDemande() {
        $demandes = $this->db->query("
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
            ORDER BY id DESC;
        ")->fetchAll(\PDO::FETCH_ASSOC);

        return $demandes;
    }

    public function getDemandeWaiting() {
        $demandes = $this->db->query("
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
            ORDER BY id DESC;
        ")->fetchAll(\PDO::FETCH_ASSOC);
        return $demandes;
    }
    
    public function getDemandeWaitingById(int $id) {
        $stmt = $this->db->prepare("
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
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateDemande(int $id, int $intervenant_id) {
        $stmt = $this->db->prepare("UPDATE Demandes SET statut = 'En cours', intervenant_id = :intervenant_id WHERE id = :id");
        $stmt->execute([':id' => $id, ':intervenant_id' => $intervenant_id]);
        return true;
    }

    public function deleteById(int $id) {
        $stmt = $this->db->prepare("DELETE FROM Demandes WHERE id = :id");
        $stmt->execute([':id' => $_POST['delete_id']]);
        return true;
    }
    
}