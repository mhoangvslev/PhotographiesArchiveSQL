<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SujetRepository")
 */
class Sujet
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
    private $idSujet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descSujet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSujet(): ?int
    {
        return $this->idSujet;
    }

    public function setIdSujet(int $idSujet): self
    {
        $this->idSujet = $idSujet;

        return $this;
    }

    public function getDescSujet(): ?string
    {
        return $this->descSujet;
    }

    public function setDescSujet(string $descSujet): self
    {
        $this->descSujet = $descSujet;

        return $this;
    }
}
