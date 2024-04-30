<?php

namespace App\Entity;

use App\Repository\EmplacementRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Centre;
  
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: EmplacementRepository::class)]

class Emplacement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idemplacement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ superficie ne doit pas être vide")]
    private ?string $superficie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ disponibilite ne doit pas être vide")]
  
    private ?string $disponibilite = null;


    #[ORM\ManyToOne(targetEntity: Centre::class)]
    #[ORM\JoinColumn(name: 'id_centre', referencedColumnName: 'id_centre')]
    private ?Centre $centre;
    
    public function getIdemplacement(): ?int
    {
        return $this->idemplacement;
    }

    public function setidemplacement(string $idemplacement): self
    {
        $this->superficie = $idemplacement;

        return $this;
    }

    public function getSuperficie(): ?int
    {
        return $this->superficie;
    }

    public function setSuperficie(string $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getDisponibilite(): ?int
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(int $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }


    public function getCentre(): ?Centre
    {
        return $this->centre;
    }

    public function setCentre(?Centre $centre): static
    {
        $this->centre = $centre;

        return $this;
    }


    
   
}


