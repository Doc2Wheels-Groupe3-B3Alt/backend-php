<?php

namespace App\Entity;

class IntervenantEntity
{
    private int $id;
    private ?string $notes;
    private ?int $horaire_id;

    public function __construct(int $id, ?string $notes = null, ?int $horaire_id = null)
    {
        $this->id = $id;
        $this->notes = $notes;
        $this->horaire_id = $horaire_id;
    }

    // Getters and setters for each property

    public function getId(): int
    {
        return $this->id;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getHoraireId(): ?int
    {
        return $this->horaire_id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function setHoraireId(?int $horaire_id): void
    {
        $this->horaire_id = $horaire_id;
    }
}
