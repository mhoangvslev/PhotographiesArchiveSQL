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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remarques;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrcli;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descdet;

    /**
     * @ORM\Column(type="integer")
     */
    private $idSerie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?int
    {
        return $this->Article;
    }

    public function setArticle(int $Article): self
    {
        $this->Article = $Article;

        return $this;
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
}
