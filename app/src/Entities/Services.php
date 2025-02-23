<?php

namespace App\Entities;

class Services extends AbstractEntity
{
    public int $id;
    public $db = (new ConnectDatabase())->execute();
    

    public function getId(): int
    {
        return $this->id;
    }


    public function getServices() {
        $services = $this->db->query("
            SELECT 
                id,
                nom,
                description
            FROM Services
            ORDER BY id DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);
        return $services;
    }
    public function getServiceById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM Services WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteService(int $id) {
        $stmt = $this->db->prepare("DELETE FROM Services WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }
    

    public function updateService(int $id, string $nom, string $description) {
        $stmt = $this->db->prepare("UPDATE Services SET nom = :nom, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function insertService(int $id, string $nom, string $description) {
        $stmt = $this->db->prepare("INSERT INTO Services (nom, description) VALUES (:nom, :description)");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    



}