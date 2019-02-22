<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexIconographiqueRepository")
 * @ORM\Table(name="IndexIconographique")
 */
class IndexIconographique implements GenericEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="idico", type="integer")
     */
    private $idico;

    /**
     * @ORM\Column(name="idx_ico", type="string", length=255)
     */
    private $idx_ico;

    public function getId(): ?int
    {
        return $this->idico;
    }

    public function getIdIco(): ?int
    {
        return $this->idico;
    }

    public function setIdIco(int $idico): self
    {
        $this->idico = $idico;

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
            "idico" => $this->idico,
            "idx_ico" => $this->idx_ico
        );
    }

    public function updateAll($entity)
    {
        $this->idico = ($entity->getIdIco() != $this->idico) ? $entity->getIdIco() : $this->idico;
        $this->idx_ico = ($entity->getIdIco() != $this->idx_ico) ? $entity->getIdIco() : $this->idx_ico;
    }


}
