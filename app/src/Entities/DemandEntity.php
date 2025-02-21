<?php

namespace App\Entity;

class DemandEntity
{
    private int $id;
    private string $date_debut;
    private string $statut;
    private ?string $date_fin;
    private ?int $user_id;
    private ?int $intervenant_id;
    private ?int $modele_id;
    private ?int $localisation_id;
    private ?int $services_id;

    public function __construct(
        int $id,
        string $date_debut,
        string $statut = 'En attente',
        ?string $date_fin = null,
        ?int $user_id = null,
        ?int $intervenant_id = null,
        ?int $modele_id = null,
        ?int $localisation_id = null,
        ?int $services_id = null
    ) {
        $this->id = $id;
        $this->date_debut = $date_debut;
        $this->statut = $statut;
        $this->date_fin = $date_fin;
        $this->user_id = $user_id;
        $this->intervenant_id = $intervenant_id;
        $this->modele_id = $modele_id;
        $this->localisation_id = $localisation_id;
        $this->services_id = $services_id;
    }

    // Getters and setters for each property

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateDebut(): string
    {
        return $this->date_debut;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateFin(): ?string
    {
        return $this->date_fin;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getIntervenantId(): ?int
    {
        return $this->intervenant_id;
    }

    public function getModeleId(): ?int
    {
        return $this->modele_id;
    }

    public function getLocalisationId(): ?int
    {
        return $this->localisation_id;
    }

    public function getServicesId(): ?int
    {
        return $this->services_id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDateDebut(string $date_debut): void
    {
        $this->date_debut = $date_debut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateFin(?string $date_fin): void
    {
        $this->date_fin = $date_fin;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setIntervenantId(?int $intervenant_id): void
    {
        $this->intervenant_id = $intervenant_id;
    }

    public function setModeleId(?int $modele_id): void
    {
        $this->modele_id = $modele_id;
    }

    public function setLocalisationId(?int $localisation_id): void
    {
        $this->localisation_id = $localisation_id;
    }

    public function setServicesId(?int $services_id): void
    {
        $this->services_id = $services_id;
    }
}
