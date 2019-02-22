<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DatePhotoRepository")
 * @ORM\Table(name="DatePhoto")
 */
class DatePhoto implements GenericEntity
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
            "iddate" => $this->iddate,
            "dateJour" => $this->dateJour,
            "dateMois" => $this->dateMois,
            "dateAnnee" => $this->dateAnnee
        );
    }

    public function getId(): ?int
    {
        return $this->iddate;
    }

    public function updateAll($entity)
    {
        $this->iddate = ($entity->getIdDate() == $this->iddate) ? $entity->getIdDate() : $this->iddate;
        $this->dateJour = ($entity->setDateJour() == $this->dateJour) ? $entity->setDateJour() : $this->dateJour;
        $this->dateMois = ($entity->getDateMois() == $this->dateMois) ? $entity->getDateMois() : $this->dateMois;
        $this->dateAnnee = ($entity->getDateAnnee() == $this->dateAnnee) ? $entity->getDateAnnee() : $this->dateAnnee;
    }

}
