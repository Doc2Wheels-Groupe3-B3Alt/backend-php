<?php

namespace App\Entity;

class UserEntity
{
    private int $id;
    private string $username;
    private string $email;
    private string $nom;
    private string $prenom;
    private string $password;
    private string $role;
    private string $date_creation;
    private ?int $adresse_id;
    private ?int $intervenant_id;
    private bool $is_verified;
    private ?string $verification_token;
    private ?string $verification_expires;

    public function __construct(
        int $id,
        string $username,
        string $email,
        string $nom,
        string $prenom,
        string $password,
        string $role = 'user',
        string $date_creation,
        ?int $adresse_id = null,
        ?int $intervenant_id = null,
        bool $is_verified = false,
        ?string $verification_token = null,
        ?string $verification_expires = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->password = $password;
        $this->role = $role;
        $this->date_creation = $date_creation;
        $this->adresse_id = $adresse_id;
        $this->intervenant_id = $intervenant_id;
        $this->is_verified = $is_verified;
        $this->verification_token = $verification_token;
        $this->verification_expires = $verification_expires;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getDateCreation(): string
    {
        return $this->date_creation;
    }

    public function getAdresseId(): ?int
    {
        return $this->adresse_id;
    }

    public function getIntervenantId(): ?int
    {
        return $this->intervenant_id;
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verification_token;
    }

    public function getVerificationExpires(): ?string
    {
        return $this->verification_expires;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setDateCreation(string $date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    public function setAdresseId(?int $adresse_id): void
    {
        $this->adresse_id = $adresse_id;
    }

    public function setIntervenantId(?int $intervenant_id): void
    {
        $this->intervenant_id = $intervenant_id;
    }

    public function setVerified(bool $is_verified): void
    {
        $this->is_verified = $is_verified;
    }

    public function setVerificationToken(?string $verification_token): void
    {
        $this->verification_token = $verification_token;
    }

    public function setVerificationExpires(?string $verification_expires): void
    {
        $this->verification_expires = $verification_expires;
    }
}
