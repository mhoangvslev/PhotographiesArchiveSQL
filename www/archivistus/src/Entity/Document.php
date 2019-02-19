<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $photoArticle;

    /**
     * @ORM\Column(type="integer")
     */
    private $discriminant;

    /**
     * @ORM\Column(type="string", length=28, nullable=true)
     */
    private $ficnum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notebp;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $referenceCindoc;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $n_v;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $c_g;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idVille;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOeuvre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idSujet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idIco;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idCliche;


    public function getPhotoArticle(): ?int
    {
        return $this->photoArticle;
    }

    public function setPhotoArticle(int $photoArticle): self
    {
        $this->photoArticle = $photoArticle;

        return $this;
    }

    public function getDiscriminant(): ?int
    {
        return $this->discriminant;
    }

    public function setDiscriminant(int $discriminant): self
    {
        $this->discriminant = $discriminant;

        return $this;
    }

    public function getFicnum(): ?string
    {
        return $this->ficnum;
    }

    public function setFicnum(?string $ficnum): self
    {
        $this->ficnum = $ficnum;

        return $this;
    }

    public function getNotebp(): ?string
    {
        return $this->notebp;
    }

    public function setNotebp(?string $notebp): self
    {
        $this->notebp = $notebp;

        return $this;
    }

    public function getReferenceCindoc(): ?string
    {
        return $this->referenceCindoc;
    }

    public function setReferenceCindoc(?string $referenceCindoc): self
    {
        $this->referenceCindoc = $referenceCindoc;

        return $this;
    }

    public function getNV(): ?string
    {
        return $this->n_v;
    }

    public function setNV(?string $n_v): self
    {
        $this->n_v = $n_v;

        return $this;
    }

    public function getCG(): ?string
    {
        return $this->c_g;
    }

    public function setCG(?string $c_g): self
    {
        $this->c_g = $c_g;

        return $this;
    }

    public function getIdVille(): ?int
    {
        return $this->idVille;
    }

    public function setIdVille(?int $idVille): self
    {
        $this->idVille = $idVille;

        return $this;
    }

    public function getIdDate(): ?int
    {
        return $this->idDate;
    }

    public function setIdDate(?int $idDate): self
    {
        $this->idDate = $idDate;

        return $this;
    }

    public function getIdOeuvre(): ?int
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(?int $idOeuvre): self
    {
        $this->idOeuvre = $idOeuvre;

        return $this;
    }

    public function getIdSujet(): ?int
    {
        return $this->idSujet;
    }

    public function setIdSujet(?int $idSujet): self
    {
        $this->idSujet = $idSujet;

        return $this;
    }

    public function getIdIco(): ?int
    {
        return $this->idIco;
    }

    public function setIdIco(?int $idIco): self
    {
        $this->idIco = $idIco;

        return $this;
    }

    public function getIdCliche(): ?int
    {
        return $this->idCliche;
    }

    public function setIdCliche(?int $idCliche): self
    {
        $this->idCliche = $idCliche;

        return $this;
    }
}
