<?php

namespace App\Entity;

class HoraireEntity
{
    private int $id;
    private string $heure_debut;
    private string $heure_fin;

    public function __construct(int $id, string $heure_debut, string $heure_fin)
    {
        $this->id = $id;
        $this->heure_debut = $heure_debut;
        $this->heure_fin = $heure_fin;
    }

    // Getters and setters for each property

    public function getId(): int
    {
        return $this->id;
    }

    public function getHeureDebut(): string
    {
        return $this->heure_debut;
    }

    public function getHeureFin(): string
    {
        return $this->heure_fin;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setHeureDebut(string $heure_debut): void
    {
        $this->heure_debut = $heure_debut;
    }

    public function setHeureFin(string $heure_fin): void
    {
        $this->heure_fin = $heure_fin;
    }
}
