<?php

namespace App\Entity;

class AdresseEntity
{
    private int $id;
    private ?string $adresse;
    private ?string $ville;
    private ?string $codePostal;
    private ?string $complement;

    public function __construct(
        int $id,
        ?string $adresse = null,
        ?string $ville = null,
        ?string $codePostal = null,
        ?string $complement = null
    ) {
        $this->id = $id;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->codePostal = $codePostal;
        $this->complement = $complement;
    }

    // Getters and setters for each property

    public function getId(): int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    public function setCodePostal(?string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    public function setComplement(?string $complement): void
    {
        $this->complement = $complement;
    }
}