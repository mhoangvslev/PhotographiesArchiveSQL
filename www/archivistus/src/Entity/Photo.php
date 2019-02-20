<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $Article;

    /**
     * @ORM\Column(name="remarques", type="string", length=255, nullable=true)
     */
    private $remarques;

    /**
     * @ORM\Column(name="nbrcli", type="integer", nullable=true)
     */
    private $nbrcli;

    /**
     * @ORM\Column(name="descdet", type="string", length=255, nullable=true)
     */
    private $descdet;

    /**
     * @ORM\Column(name="idSerie", type="integer")
     */
    private $idSerie;


    public function getArticle(): ?int
    {
        return $this->Article;
    }

    public function getRemarques(): ?string
    {
        return $this->remarques;
    }

    public function setRemarques(?string $remarques): self
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function getNbrcli(): ?int
    {
        return $this->nbrcli;
    }

    public function setNbrcli(?int $nbrcli): self
    {
        $this->nbrcli = $nbrcli;

        return $this;
    }

    public function getDescdet(): ?string
    {
        return $this->descdet;
    }

    public function setDescdet(?string $descdet): self
    {
        $this->descdet = $descdet;

        return $this;
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

    public function toArray(){
        return array(
            $this->Article,
            $this->remarques,
            $this->nbrcli,
            $this->descdet,
            $this->idSerie
        );
    }
}
