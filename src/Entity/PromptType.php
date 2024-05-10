<?php

namespace App\Entity;

use App\Repository\PromptTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromptTypeRepository::class)]
class PromptType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promptPrefix = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promptSuffix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPromptPrefix(): ?string
    {
        return $this->promptPrefix;
    }

    public function setPromptPrefix(?string $promptPrefix): static
    {
        $this->promptPrefix = $promptPrefix;

        return $this;
    }

    public function getPromptSuffix(): ?string
    {
        return $this->promptSuffix;
    }

    public function setPromptSuffix(?string $promptSuffix): static
    {
        $this->promptSuffix = $promptSuffix;

        return $this;
    }
}
