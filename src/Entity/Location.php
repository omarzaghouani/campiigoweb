<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="location")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idLoc;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEmprunt;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer")
     */
    private $cinUtil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $equip;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrequipements;

    /**
     * @ORM\Column(type="float")
     */
    private $cout;

    public function getIdLoc(): ?int
    {
        return $this->idLoc;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): self
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCinUtil(): ?int
    {
        return $this->cinUtil;
    }

    public function setCinUtil(int $cinUtil): self
    {
        $this->cinUtil = $cinUtil;

        return $this;
    }

    public function getEquip(): ?string
    {
        return $this->equip;
    }

    public function setEquip(string $equip): self
    {
        $this->equip = $equip;

        return $this;
    }

    public function getNbrequipements(): ?int
    {
        return $this->nbrequipements;
    }

    public function setNbrequipements(int $nbrequipements): self
    {
        $this->nbrequipements = $nbrequipements;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): self
    {
        $this->cout = $cout;

        return $this;
    }
}
