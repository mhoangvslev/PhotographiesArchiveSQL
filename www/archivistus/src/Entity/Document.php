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
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville")
     * @ORM\JoinColumn(name="idVille", referencedColumnName="idVille")
     */
    private $idville;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexIconographique")
     * @ORM\JoinColumn(name="idico", referencedColumnName="idico")
     */
    private $idico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DatePhoto")
     * @ORM\JoinColumn(name="iddate", referencedColumnName="iddate")
     */
    private $iddate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOeuvre")
     * @ORM\JoinColumn(name="idoeuvre", referencedColumnName="idtype")
     */
    private $idoeuvre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexPersonne")
     * @ORM\JoinColumn(name="idsujet", referencedColumnName="idoeuvre")
     */
    private $idsujet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliche")
     * @ORM\JoinColumn(name="idcliche", referencedColumnName="idcliche")
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

    public function getIdville(): ?Ville
    {
        return $this->idville;
    }

    public function setIdville(?Ville $idville): self
    {
        $this->idville = $idville;

        return $this;
    }

    public function getIdico(): ?IndexIconographique
    {
        return $this->idico;
    }

    public function setIdico(?IndexIconographique $idico): self
    {
        $this->idico = $idico;

        return $this;
    }

    public function getIddate(): ?DatePhoto
    {
        return $this->iddate;
    }

    public function setIddate(?DatePhoto $iddate): self
    {
        $this->iddate = $iddate;

        return $this;
    }

    public function getIdoeuvre(): ?TypeOeuvre
    {
        return $this->idoeuvre;
    }

    public function setIdoeuvre(?TypeOeuvre $idoeuvre): self
    {
        $this->idoeuvre = $idoeuvre;

        return $this;
    }

    public function getIdsujet(): ?IndexPersonne
    {
        return $this->idsujet;
    }

    public function setIdsujet(?IndexPersonne $idsujet): self
    {
        $this->idsujet = $idsujet;

        return $this;
    }

    public function getIdcliche(): ?Cliche
    {
        return $this->idcliche;
    }

    public function setIdcliche(?Cliche $idcliche): self
    {
        $this->idcliche = $idcliche;

        return $this;
    }
}
