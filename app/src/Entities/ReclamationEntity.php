<?php

namespace App\Entity;

class ReclamationEntity
{
    private int $id;
    private string $type;
    private string $description;
    private string $date_creation;
    private string $statut;
    private ?int $note;
    private ?int $user_id;
    private ?int $demande_id;
    private ?string $reponse;
    private ?string $date_reponse;
    private ?int $moderateur_id;

    public function __construct(
        int $id,
        string $type,
        string $description,
        string $date_creation,
        string $statut = 'en_attente',
        ?int $note = null,
        ?int $user_id = null,
        ?int $demande_id = null,
        ?string $reponse = null,
        ?string $date_reponse = null,
        ?int $moderateur_id = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->description = $description;
        $this->date_creation = $date_creation;
        $this->statut = $statut;
        $this->note = $note;
        $this->user_id = $user_id;
        $this->demande_id = $demande_id;
        $this->reponse = $reponse;
        $this->date_reponse = $date_reponse;
        $this->moderateur_id = $moderateur_id;
    }

    // Getters and setters for each property

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDateCreation(): string
    {
        return $this->date_creation;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getDemandeId(): ?int
    {
        return $this->demande_id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function getDateReponse(): ?string
    {
        return $this->date_reponse;
    }

    public function getModerateurId(): ?int
    {
        return $this->moderateur_id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setDateCreation(string $date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setNote(?int $note): void
    {
        $this->note = $note;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setDemandeId(?int $demande_id): void
    {
        $this->demande_id = $demande_id;
    }

    public function setReponse(?string $reponse): void
    {
        $this->reponse = $reponse;
    }

    public function setDateReponse(?string $date_reponse): void
    {
        $this->date_reponse = $date_reponse;
    }

    public function setModerateurId(?int $moderateur_id): void
    {
        $this->moderateur_id = $moderateur_id;
    }
}