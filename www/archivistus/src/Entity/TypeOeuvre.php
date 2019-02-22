<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeOeuvreRepository")
 * @ORM\Table(name="TypeOeuvre")
 */
class TypeOeuvre implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idtype", type="integer")
     */
    private $idtype;

    /**
     * @ORM\Column(name="nomType", type="string", length=255)
     */
    private $nomType;

    public function getId(): ?int
    {
        return $this->idtype;
    }

    public function getIdType(): ?int
    {
        return $this->idtype;
    }

    public function setIdType(int $idtype): self
    {
        $this->idtype = $idtype;

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
            "idtype" => $this->idtype,
            "nomtype" => $this->nomType
        );
    }

    public function updateAll($entity)
    {
        $this->idtype = ($entity->getIdType() != $this->idtype) ? $entity->getIdType() : $this->idtype;
        $this->nomType = ($entity->getNomType() != $this->nomType) ? $entity->getNomType() : $this->nomType;
    }
}
