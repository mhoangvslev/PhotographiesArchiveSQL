<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 * @ORM\Table(name="Serie")
 */
class Serie
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
            $this->idserie,
            $this->nomSerie
        );
    }
}
