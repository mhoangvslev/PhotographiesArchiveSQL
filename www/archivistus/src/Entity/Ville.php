<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 * @ORM\Table(name="Ville")
 */
class Ville implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idville", type="integer")
     */
    private $idville;

    /**
     * @ORM\Column(name="nomVille", type="string", length=255)
     */
    private $nomVille;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coordx;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coordy;


    public function getIdVille(): ?int
    {
        return $this->idville;
    }

    public function setIdVille(int $idville): self
    {
        $this->idville = $idville;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(string $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCoordx(): ?float
    {
        return $this->coordx;
    }

    public function setCoordx(?float $coordx): self
    {
        $this->coordx = $coordx;

        return $this;
    }

    public function getCoordy(): ?float
    {
        return $this->coordy;
    }

    public function setCoordy(?float $coordy): self
    {
        $this->coordy = $coordy;

        return $this;
    }

    public function toArray(){
        return array(
            "idville" => $this->idville,
            "nomVille" => $this->nomVille,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "coordx" => $this->coordx,
            "coordy" => $this->coordy
        );
    }

    public function getId(): ?int
    {
        return $this->getIdVille();
    }

    public function updateAll($entity)
    {
        $this->idville = ($entity->getIdVille() != $this->idville) ? $entity->getIdVille() : $this->idville;
        $this->nomVille = ($entity->getNomVille() != $this->nomVille) ? $entity->getNomVille() : $this->nomVille;
        $this->latitude = ($entity->getLatitude() != $this->latitude) ? $entity->getLongitude() : $this->latitude;
        $this->longitude = ($entity->getLongitude() != $this->longitude) ? $entity->getLongitude() : $this->longitude;
        $this->coordx = ($entity->getCoordx() != $this->coordx) ? $entity->getCoordx() : $this->coordx;
        $this->coordy = ($entity->getCoordy() != $this->coordy) ? $entity->getCoordy() : $this->coordy;
    }


}
