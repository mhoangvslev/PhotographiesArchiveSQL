<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClicheRepository")
 * @ORM\Table(name="Cliche")
 */
class Cliche
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idCliche", type="integer")
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

    public function toArray(){
        return array(
            $this->idcliche,
            $this->taille
        );
    }
}
