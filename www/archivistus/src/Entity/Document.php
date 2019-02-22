<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document implements GenericEntity
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     */
    private $photoarticle;

    /**
     * @ORM\Id @ORM\Column(type="integer")
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
     * @ORM\JoinColumn(name="idville", referencedColumnName="idville")
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


    public function getIdVille(): ?Ville
    {
        return $this->idville;
    }

    public function setIdVille(?Ville $idville): self
    {
        $this->idville = $idville;

        return $this;
    }

    public function getIdIco(): ?IndexIconographique
    {
        return $this->idico;
    }

    public function setIdIco(?IndexIconographique $idico): self
    {
        $this->idico = $idico;

        return $this;
    }

    public function getIdDate(): ?DatePhoto
    {
        return $this->iddate;
    }

    public function setIdDate(?DatePhoto $iddate): self
    {
        $this->iddate = $iddate;

        return $this;
    }

    public function getIdOeuvre(): ?TypeOeuvre
    {
        return $this->idoeuvre;
    }

    public function setIdOeuvre(?TypeOeuvre $idoeuvre): self
    {
        $this->idoeuvre = $idoeuvre;

        return $this;
    }

    public function getIdSujet(): ?IndexPersonne
    {
        return $this->idsujet;
    }

    public function setIdSujet(?IndexPersonne $idsujet): self
    {
        $this->idsujet = $idsujet;

        return $this;
    }

    public function getIdCliche(): ?Cliche
    {
        return $this->idcliche;
    }

    public function setIdCliche(?Cliche $idcliche): self
    {
        $this->idcliche = $idcliche;

        return $this;
    }

    public function  toArray(): ?array{
        return array(
            "photoarticle" => $this->photoarticle,
            "discriminant" => $this->discriminant,
            "ficnum" => $this->ficnum,
            "notebp" => $this->notebp,
            "referencecindoc" => $this->referencecindoc,
            "n_v" => $this->n_v,
            "c_g" => $this->c_g,
            "idville" => $this->idville,
            "iddate" => $this->iddate,
            "idoeuvre" => $this->idoeuvre,
            "idsujet" => $this->idsujet,
            "idico" => $this->idico,
            "idcliche" => $this->idcliche
        );
    }

    public function getId(): ?int
    {
        return $this->photoarticle;
    }

    public function updateAll($entity)
    {
        $this->photoarticle = ($entity->getPhotoArticle() != $this->photoarticle) ? $entity->getPhotoArticle() : $this->photoarticle;
        $this->discriminant = ($entity->getDiscriminant() != $this->discriminant) ? $entity->getDiscriminant() : $this->discriminant;
        $this->ficnum = ($entity->getFicnum() != $this->ficnum) ? $entity->getFicnum() : $this->ficnum;
        $this->notebp = ($entity->getNotebp() != $this->notebp) ? $entity->getNotebp() : $this->notebp;
        $this->referencecindoc = ($entity->getReferenceCindoc() != $this->referencecindoc) ? $entity->getReferenceCindoc() : $this->referencecindoc;
        $this->n_v = ($entity->getNV() != $this->n_v) ? $entity->getNV() : $this->n_v;
        $this->c_g = ($entity->getCG() != $this->c_g) ? $entity->getCG() : $this->c_g;
        $this->idville = ($entity->getIdVille() != $this->idville) ? $entity->getIdVille() : $this->idville;
        $this->iddate = ($entity->getIdDate() != $this->iddate) ? $entity->getIdDate() : $this->iddate;
        $this->idoeuvre = ($entity->getIdOeuvre() != $this->idoeuvre) ? $entity->getIdOeuvre() : $this->idoeuvre;
        $this->idsujet = ($entity->getIdSujet() != $this->idsujet) ? $entity->getIdSujet() : $this->idsujet;
        $this->idico = ($entity->getIdIco() != $this->idico) ? $entity->getIdIco() : $this->idico;
        $this->idcliche = ($entity->getIdCliche() != $this->idcliche) ? $entity->getIdCliche() : $this->idcliche;
    }


}
