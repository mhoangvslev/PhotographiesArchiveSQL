<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClicheRepository")
 * @ORM\Table(name="Cliche")
 */
class Cliche implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idcliche", type="integer")
     */
    private $idcliche;

    /**
     * @ORM\Column(name="Taille", type="string", length=255, nullable=true)
     */
    private $taille;


    public function getIdCliche(): ?int
    {
        return $this->idcliche;
    }

    public function setIdCliche(int $idcliche): self
    {
        $this->idcliche = $idcliche;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Convert entity to array
     * @return array for display
     */
    public function toArray(){
        return array(
            "idcliche" => $this->idcliche,
            "taille" => $this->taille
        );
    }

    public function getId(): ?int
    {
        return $this->getIdCliche();
    }

    public function updateAll($entity)
    {
        $this->idcliche = ($entity->getIdCliche() != $this->idcliche) ? $entity->getIdCliche() : $this->idcliche;
        $this->taille = ($entity->getTaille() != $this->taille) ? $entity->getTaille() : $this->taille;
    }


}
