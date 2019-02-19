<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DatePhotoRepository")
 */
class DatePhoto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $idDate;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $dateJour;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $dateMois;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $dateAnnee;

    public function getIdDate(): ?int
    {
        return $this->idDate;
    }

    public function setIdDate(int $idDate): self
    {
        $this->idDate = $idDate;

        return $this;
    }

    public function getDateJour(): ?string
    {
        return $this->dateJour;
    }

    public function setDateJour(?string $dateJour): self
    {
        $this->dateJour = $dateJour;

        return $this;
    }

    public function getDateMois(): ?string
    {
        return $this->dateMois;
    }

    public function setDateMois(?string $dateMois): self
    {
        $this->dateMois = $dateMois;

        return $this;
    }

    public function getDateAnnee(): ?string
    {
        return $this->dateAnnee;
    }

    public function setDateAnnee(string $dateAnnee): self
    {
        $this->dateAnnee = $dateAnnee;

        return $this;
    }
}
