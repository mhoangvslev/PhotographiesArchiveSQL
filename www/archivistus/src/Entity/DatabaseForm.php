<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TableFormRepository")
 */
class DatabaseForm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tableName;

    /**
     * @ORM\Column(type="integer")
     */
    private $queryLimit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $queryField;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getQueryLimit(): ?int
    {
        return $this->queryLimit;
    }

    public function setQueryLimit(int $queryLimit): self
    {
        $this->queryLimit = $queryLimit;

        return $this;
    }

    public function getQueryField(): ?string
    {
        return $this->queryField;
    }

    public function setQueryField(?string $queryField): self
    {
        $this->queryField = $queryField;

        return $this;
    }
}
