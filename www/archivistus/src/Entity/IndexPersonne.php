<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexPersonneRepository")
 * @ORM\Table(name="IndexPersonne")
 */
class IndexPersonne
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idoeuvre", type="integer")
     */
    private $idoeuvre;

    /**
     * @ORM\Column(name="nomOeuvre", type="string", length=255)
     */
    private $nomOeuvre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOeuvre")
     */
    private $typeoeuvre;


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
        return $this->nomOeuvre;
    }

    public function setNomOeuvre(string $nomOeuvre): self
    {
        $this->nomOeuvre = $nomOeuvre;

        return $this;
    }

    public function toArray(){
        return array(
            $this->idoeuvre,
            $this->nomOeuvre,
            $this->typeOeuvre
        );
    }

    public function getTypeoeuvre(): ?TypeOeuvre
    {
        return $this->typeoeuvre;
    }

    public function setTypeoeuvre(?TypeOeuvre $typeoeuvre): self
    {
        $this->typeoeuvre = $typeoeuvre;

        return $this;
    }
}
