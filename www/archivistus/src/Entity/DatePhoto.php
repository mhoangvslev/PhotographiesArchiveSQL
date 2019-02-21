<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DatePhotoRepository")
 * @ORM\Table(name="DatePhoto")
 */
class DatePhoto
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="iddate", type="integer")
     */
    private $iddate;

    /**
     * @ORM\Column(name = "dateJour", type="string", length=2, nullable=true)
     */
    private $dateJour;

    /**
     * @ORM\Column(name="dateMois", type="string", length=2, nullable=true)
     */
    private $dateMois;

    /**
     * @ORM\Column(name="dateAnnee", type="string", length=4)
     */
    private $dateAnnee;

    public function getIdDate(): ?int
    {
        return $this->iddate;
    }

    public function setIdDate(int $iddate): self
    {
        $this->iddate = $iddate;

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

    public function toArray(){
        return array(
            $this->iddate,
            $this->dateJour,
            $this->dateMois,
            $this->dateAnnee
        );
    }
}
