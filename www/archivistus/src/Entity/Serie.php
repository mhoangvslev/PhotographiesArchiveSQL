<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 */
class Serie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idSerie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomSerie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSerie(): ?int
    {
        return $this->idSerie;
    }

    public function setIdSerie(int $idSerie): self
    {
        $this->idSerie = $idSerie;

        return $this;
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
}
