<?php

namespace App\Entity;

use App\Repository\HealthDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HealthDataRepository::class)]
class HealthData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $diet = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $oxygen = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $norton = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $dependency = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $saline = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $glucose = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $trans = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ingestion = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $diuresis = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $sweat = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $expectoration = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $depositions = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $drains = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?patient $patient = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?nurse $nurse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getDiet(): ?string
    {
        return $this->diet;
    }

    public function setDiet(?string $diet): static
    {
        $this->diet = $diet;

        return $this;
    }

    public function getOxygen(): ?string
    {
        return $this->oxygen;
    }

    public function setOxygen(?string $oxygen): static
    {
        $this->oxygen = $oxygen;

        return $this;
    }

    public function getNorton(): ?string
    {
        return $this->norton;
    }

    public function setNorton(?string $norton): static
    {
        $this->norton = $norton;

        return $this;
    }

    public function getDependency(): ?string
    {
        return $this->dependency;
    }

    public function setDependency(?string $dependency): static
    {
        $this->dependency = $dependency;

        return $this;
    }

    public function getSaline(): ?string
    {
        return $this->saline;
    }

    public function setSaline(?string $saline): static
    {
        $this->saline = $saline;

        return $this;
    }

    public function getGlucose(): ?string
    {
        return $this->glucose;
    }

    public function setGlucose(?string $glucose): static
    {
        $this->glucose = $glucose;

        return $this;
    }

    public function getTrans(): ?string
    {
        return $this->trans;
    }

    public function setTrans(?string $trans): static
    {
        $this->trans = $trans;

        return $this;
    }

    public function getIngestion(): ?string
    {
        return $this->ingestion;
    }

    public function setIngestion(?string $ingestion): static
    {
        $this->ingestion = $ingestion;

        return $this;
    }

    public function getDiuresis(): ?string
    {
        return $this->diuresis;
    }

    public function setDiuresis(?string $diuresis): static
    {
        $this->diuresis = $diuresis;

        return $this;
    }

    public function getSweat(): ?string
    {
        return $this->sweat;
    }

    public function setSweat(?string $sweat): static
    {
        $this->sweat = $sweat;

        return $this;
    }

    public function getExpectoration(): ?string
    {
        return $this->expectoration;
    }

    public function setExpectoration(?string $expectoration): static
    {
        $this->expectoration = $expectoration;

        return $this;
    }

    public function getDepositions(): ?string
    {
        return $this->depositions;
    }

    public function setDepositions(?string $depositions): static
    {
        $this->depositions = $depositions;

        return $this;
    }

    public function getDrains(): ?string
    {
        return $this->drains;
    }

    public function setDrains(?string $drains): static
    {
        $this->drains = $drains;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPatient(): ?patient
    {
        return $this->patient;
    }

    public function setPatient(?patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getNurse(): ?nurse
    {
        return $this->nurse;
    }

    public function setNurse(?nurse $nurse): static
    {
        $this->nurse = $nurse;

        return $this;
    }
}
