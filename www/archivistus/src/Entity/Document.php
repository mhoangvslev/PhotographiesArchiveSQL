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
    private $photoarticle;

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
    private $referencecindoc;

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
    private $idville;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $iddate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idoeuvre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idsujet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idico;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idcliche;


    public function getPhotoArticle(): ?int
    {
        return $this->photoarticle;
    }

    public function setPhotoArticle(int $photoarticle): self
    {
        $this->photoarticle = $photoarticle;

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
        return $this->referencecindoc;
    }

    public function setReferenceCindoc(?string $referencecindoc): self
    {
        $this->referencecindoc = $referencecindoc;

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
        return $this->idville;
    }

    public function setIdVille(?int $idville): self
    {
        $this->idville = $idville;

        return $this;
    }

    public function getIdDate(): ?int
    {
        return $this->iddate;
    }

    public function setIdDate(?int $iddate): self
    {
        $this->iddate = $iddate;

        return $this;
    }

    public function getIdOeuvre(): ?int
    {
        return $this->idoeuvre;
    }

    public function setIdOeuvre(?int $idoeuvre): self
    {
        $this->idoeuvre = $idoeuvre;

        return $this;
    }

    public function getIdSujet(): ?int
    {
        return $this->idsujet;
    }

    public function setIdSujet(?int $idsujet): self
    {
        $this->idsujet = $idsujet;

        return $this;
    }

    public function getIdIco(): ?int
    {
        return $this->idico;
    }

    public function setIdIco(?int $idico): self
    {
        $this->idico = $idico;

        return $this;
    }

    public function getIdCliche(): ?int
    {
        return $this->idcliche;
    }

    public function setIdCliche(?int $idcliche): self
    {
        $this->idcliche = $idcliche;

        return $this;
    }

    public function  toArray(): ?array{
        return array(
            $this->photoarticle,
            $this->discriminant,
            $this->ficnum,
            $this->notebp,
            $this->referencecindoc,
            $this->n_v,
            $this->c_g,
            $this->idville,
            $this->iddate,
            $this->idoeuvre,
            $this->idsujet,
            $this->idico,
            $this->idcliche
        );
    }
}
