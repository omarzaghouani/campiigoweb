<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[Assert\NotBlank(message: "Le champ Nom ne doit pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $nom;

    #[Assert\NotBlank(message: "Le champ prenom ne doit pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $prenom;

    #[Assert\NotBlank(message: "Le champ Num ne doit pas être vide")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Invalide. Il doit contenir 8 chiffres."
    )]
    #[ORM\Column(length: 255)]
    private ?int $numerodetelephone;

    #[Assert\NotBlank(message: "Le champ Email ne doit pas être vide")]
    #[Assert\Email(message: "Le champ Email doit être une adresse email valide")]
    #[ORM\Column(length: 255)]
    private ?string $email;

    #[Assert\NotBlank(message: "Le champ mot de passe ne doit pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_d = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getNumerodetelephone(): ?int
    {
        return $this->numerodetelephone;
    }

    public function setNumerodetelephone(int $numerodetelephone): self
    {
        $this->numerodetelephone = $numerodetelephone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPhotoD(): ?string
    {
        return $this->photo_d;
    }

    public function setPhotoD(?string $photo_d): self
    {
        $this->photo_d = $photo_d;
        return $this;
    }

    

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
{
    return (string) $this->getUserIdentifier(); // This returns the email as the user identifier.
}

    public function eraseCredentials(): void
    {
        // Intentionally left blank
    }

    public function getSalt(): ?string
    {
        return null; // Not necessary if you're using a modern algorithm like bcrypt or argon2
    }
}
