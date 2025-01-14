<?php

namespace App\Entity;

use App\Repository\NurseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NurseRepository::class)]
class Nurse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $first_surname = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $second_surname = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'blob', nullable: true)]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstSurname(): ?string
    {
        return $this->first_surname;
    }

    public function setFirstSurname(?string $first_surname): static
    {
        $this->first_surname = $first_surname;

        return $this;
    }

    public function getSecondSurname(): ?string
    {
        return $this->second_surname;
    }

    public function setSecondSurname(?string $second_surname): static
    {
        $this->second_surname = $second_surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }
    
    public function getImage(): ?string
    {
        if ($this->image !== null) {
            return base64_encode(is_resource($this->image) ? stream_get_contents($this->image) : $this->image);
        }
        return null;
    }

}
