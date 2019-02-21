<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SujetRepository")
 * @ORM\Table(name="Sujet")
 */
class Sujet
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idsujet", type="integer")
     */
    private $idsujet;

    /**
     * @ORM\Column(name="descSujet", type="string", length=255)
     */
    private $descSujet;

    public function getIdSujet(): ?int
    {
        return $this->idsujet;
    }

    public function setIdSujet(int $idsujet): self
    {
        $this->idsujet = $idsujet;

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

    public function toArray(){
        return array(
            $this->idsujet,
            $this->descSujet
        );
    }
}
