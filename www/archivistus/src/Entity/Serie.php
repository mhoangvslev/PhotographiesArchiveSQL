<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 * @ORM\Table(name="Serie")
 */
class Serie implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idserie", type="integer")
     */
    private $idserie;

    /**
     * @ORM\Column(name="nomSerie", type="string", length=255)
     */
    private $nomSerie;


    public function getIdSerie(): ?int
    {
        return $this->idserie;
    }

    public function getNomSerie(): ?string
    {
        return $this->nomSerie;
    }

    public function setNomSerie(string $nomSerie): self
    {
        $this->nomSerie = $nomSerie;

        return $this;
    }

    public function toArray(){
        return array(
            "idserie" => $this->idserie,
            "nomserie" => $this->nomSerie
        );
    }

    public function getId(): ?int
    {
        return $this->getIdSerie();
    }

    public function updateAll($entity)
    {
        $this->idserie = ($entity->getIdSerie() != $this->idserie) ? $entity->getIdSerie() : $this->idserie;
        $this->nomSerie = ($entity->getNomSerie() != $this->nomSerie) ? $entity->getNomSerie() : $this->nomSerie;
    }


}
