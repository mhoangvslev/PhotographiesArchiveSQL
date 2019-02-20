<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeOeuvreRepository")
 * @ORM\Table(name="TypeOeuvre")
 */
class TypeOeuvre
{
    /**
     * @ORM\Column(name="idType", type="integer")
     */
    private $idType;

    /**
     * @ORM\Id()
     * @ORM\Column(name="nomType", type="string", length=255)
     */
    private $nomType;

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setIdType(int $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): self
    {
        $this->nomType = $nomType;

        return $this;
    }

    public function toArray(){
        return array(
            $this->idType,
            $this->nomType
        );
    }
}
