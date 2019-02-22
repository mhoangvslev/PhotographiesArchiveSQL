<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexPersonneRepository")
 * @ORM\Table(name="IndexPersonne")
 */
class IndexPersonne implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idoeuvre", type="integer")
     */
    private $idoeuvre;

    /**
     * @ORM\Column(name="nomoeuvre", type="string", length=255)
     */
    private $nomoeuvre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOeuvre")
     * @ORM\JoinColumn(name="typeoeuvre", referencedColumnName="idtype")
     */
    private $typeoeuvre;

    public function getId(): ?int
    {
        return $this->idoeuvre;
    }

    public function getIdOeuvre(): ?int
    {
        return $this->idoeuvre;
    }

    public function setIdOeuvre(int $idoeuvre): self
    {
        $this->idoeuvre = $idoeuvre;

        return $this;
    }

    public function getNomOeuvre(): ?string
    {
        return $this->nomoeuvre;
    }

    public function setNomOeuvre(string $nomoeuvre): self
    {
        $this->nomoeuvre = $nomoeuvre;

        return $this;
    }

    public function toArray(){
        return array(
            "idoeuvre" => $this->idoeuvre,
            "nomoeuvre" => $this->nomoeuvre,
            "typepeuvre" => $this->typeoeuvre
        );
    }

    public function getTypeOeuvre(): ?TypeOeuvre
    {
        return $this->typeoeuvre;
    }

    public function setTypeOeuvre(?TypeOeuvre $typeoeuvre): self
    {
        $this->typeoeuvre = $typeoeuvre;

        return $this;
    }

    public function updateAll($entity)
    {
        $this->idoeuvre = ($this->idoeuvre != $entity->getIdOeuvre()) ? $entity->getIdOeuvre() : $this->idoeuvre;
        $this->nomoeuvre = ($this->nomoeuvre != $entity->getNomEuvre()) ? $entity->getNomOeuvre() : $this->nomoeuvre;
        $this->typeoeuvre = ($this->typeoeuvre != $entity->getTypeOeuvre()) ? $entity->getTypeOeuvre() : $this->typeoeuvre;
    }
}
