<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexIconographiqueRepository")
 * @ORM\Table(name="IndexIconographique")
 */
class IndexIconographique
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idIco", type="integer")
     */
    private $idIco;

    /**
     * @ORM\Column(name="idx_ico", type="string", length=255)
     */
    private $idx_ico;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdIco(): ?int
    {
        return $this->idIco;
    }

    public function setIdIco(int $idIco): self
    {
        $this->idIco = $idIco;

        return $this;
    }

    public function getIdxIco(): ?string
    {
        return $this->idx_ico;
    }

    public function setIdxIco(string $idx_ico): self
    {
        $this->idx_ico = $idx_ico;

        return $this;
    }

    public function toArray(){
        return array(
            $this->idIco,
            $this->idx_ico
        );
    }
}
