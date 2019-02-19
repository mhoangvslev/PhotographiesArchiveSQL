<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClicheRepository")
 */
class Cliche
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $idCliche;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taille;


    public function getIdCliche(): ?int
    {
        return $this->idCliche;
    }

    public function setIdCliche(int $idCliche): self
    {
        $this->idCliche = $idCliche;

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
}
