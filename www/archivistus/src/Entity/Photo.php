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
    private $article;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Serie")
     */
    private $idserie;


    public function getarticle(): ?int
    {
        return $this->article;
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

    public function toArray(){
        return array(
            $this->article,
            $this->remarques,
            $this->nbrcli,
            $this->descdet,
            $this->idserie
        );
    }

    public function getIdserie(): ?Serie
    {
        return $this->idserie;
    }

    public function setIdserie(?Serie $idserie): self
    {
        $this->idserie = $idserie;

        return $this;
    }
}
