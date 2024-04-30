<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EquipementRepository::class)]
class Equipement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_equipement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ libelle ne doit pas être vide")]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ categorie ne doit pas être vide")]
    private ?string $categorie = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ prix ne doit pas être vide")]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ prix ne doit pas être vide")]
    private ?int $nbrUnite = null;

    public function getIdEquipement(): ?int
    {
        return $this->id_equipement;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getNbrUnite(): ?int
    {
        return $this->nbrUnite;
    }

    public function setNbrUnite(?int $nbrUnite): static
    {
        $this->nbrUnite = $nbrUnite;
        return $this;
    }
}
