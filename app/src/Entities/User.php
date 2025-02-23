<?php

namespace App\Entities;

class User extends AbstractEntity
{
    public int $id;
    public $db = (new ConnectDatabase())->execute();
    

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUsers() {
        $users = $this->db->query("
            SELECT 
                id,
                username,
                email,
                nom,
                prenom,
                role,
                TO_CHAR(date_creation, 'DD/MM/YYYY HH24:MI') as date_creation
            FROM Utilisateurs
            WHERE role = 'user'
            ORDER BY date_creation DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }

    public function getIntervenant(int $id) {
        
        $stmt = $this->db->prepare("
            SELECT DISTINCT * FROM Utilisateurs WHERE intervenant_id = :id;
        ");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
   }

   public function getTechniciens(string $ville) {
        
        $stmt = $this->db->prepare("
            SELECT u.* FROM Utilisateurs u
            LEFT JOIN Adresses a ON u.adresse_id = a.id
            WHERE u.role = 'technicien'
            AND a.ville = :ville;
            ;
        ");

        $stmt->execute([':ville' => $ville]);
        

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
   }


   public function getAdminTechnicien() {
        $users = $this->db->query("
            SELECT 
                u.id,
                u.username,
                u.email,
                u.nom,
                u.prenom,
                u.role,
                TO_CHAR(u.date_creation, 'DD/MM/YYYY HH24:MI') as date_creation,
                ht.heure_debut,
                ht.heure_fin
            FROM Utilisateurs u
            LEFT JOIN Intervenants i ON u.intervenant_id = i.id
            LEFT JOIN HorairesTravail ht ON i.horaire_id = ht.id
            WHERE u.role = 'technicien'
            ORDER BY u.date_creation DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
   }

   public function deleteById(int $id) {
        $stmt = $this->db->prepare("DELETE FROM Utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return true;
   }

   public function updatePassword(string $password, int $id, string $role, string $email, string $username, string $nom, string $prenom) {
        $stmt = $this->db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, password = :password, role = :role WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return true;
   }

   public function updateWithoutPassword(int $id, string $role, string $email, string $username, string $nom, string $prenom) {
        $stmt = $this->db->prepare("UPDATE Utilisateurs SET prenom = :prenom, nom = :nom, username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return true;
   }

   public function insertUser(string $password, string $role, string $email, string $username, string $nom, string $prenom) {
        $stmt = $this->db->prepare("INSERT INTO Utilisateurs (prenom, nom, username, email, password, role) VALUES (:prenom, :nom, :username, :email, :password, :role)");
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return true;
   }

}