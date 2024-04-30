<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numReservation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateD = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateF = null;

    #[ORM\Column]
    private ?int $nbr_personnes = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Emplacement $idemplacement = null;

    #[ORM\Column]
    private ?int $cout = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumReservation(): ?int
    {
        return $this->numReservation;
    }

    public function setNumReservation(int $numReservation): static
    {
        $this->numReservation = $numReservation;

        return $this;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->dateD;
    }

    public function setDateD(\DateTimeInterface $dateD): static
    {
        $this->dateD = $dateD;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->dateF;
    }

    public function setDateF(\DateTimeInterface $dateF): static
    {
        $this->dateF = $dateF;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->nbr_personnes;
    }

    public function setNbrPersonnes(int $nbr_personnes): static
    {
        $this->nbr_personnes = $nbr_personnes;

        return $this;
    }

    public function getIdemplacement(): ?Emplacement
    {
        return $this->idemplacement;
    }

    public function setIdemplacement(Emplacement $idemplacement): static
    {
        $this->idemplacement = $idemplacement;

        return $this;
    }

    public function getCout(): ?int
    {
        return $this->cout;
    }

    public function setCout(int $cout): static
    {
        $this->cout = $cout;

        return $this;
    }
}
