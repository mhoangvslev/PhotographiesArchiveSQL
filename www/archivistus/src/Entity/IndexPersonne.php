<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexPersonneRepository")
 */
class IndexPersonne
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
    private $idOeuvre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomOeuvre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOeuvre;

    public function getId(): ?int
    {
        return $this->id;
    }

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
}
