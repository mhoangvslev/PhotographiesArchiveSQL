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
     * @ORM\Column(name="idOeuvre", type="integer")
     */
    private $idOeuvre;

    /**
     * @ORM\Column(name="nomOeuvre", type="string", length=255)
     */
    private $nomOeuvre;

    /**
     * @ORM\Column(name="typeOeuvre", type="string", length=255)
     */
    private $typeOeuvre;

    public function getIdOeuvre(): ?int
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(int $idOeuvre): self
    {
        $this->idOeuvre = $idOeuvre;

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

    public function getTypeOeuvre(): ?string
    {
        return $this->typeOeuvre;
    }

    public function setTypeOeuvre(string $typeOeuvre): self
    {
        $this->typeOeuvre = $typeOeuvre;

        return $this;
    }

    public function toArray(){
        return array(
            $this->idOeuvre,
            $this->nomOeuvre,
            $this->typeOeuvre
        );
    }
}
