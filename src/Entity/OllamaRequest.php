<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\OllamaRequestRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ])
]
#[ORM\Entity(repositoryClass: OllamaRequestRepository::class)]
class OllamaRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $input = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $output = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pickedUpByWorkerAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $doneAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promptPrefix = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promptSuffix = null;

    public function __construct() {
        $this->setCreatedAt(new DateTimeImmutable());
        $this->status = 'NEW';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setInput(?string $input): static
    {
        $this->input = $input;

        return $this;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(?string $output): static
    {
        $this->output = $output;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPickedUpByWorkerAt(): ?\DateTimeImmutable
    {
        return $this->pickedUpByWorkerAt ?? null;
    }

    public function setPickedUpByWorkerAt(?\DateTimeImmutable $pickedUpByWorkerAt): static
    {
        $this->pickedUpByWorkerAt = $pickedUpByWorkerAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeImmutable
    {
        return $this->doneAt ?? null;
    }

    public function setDoneAt(?\DateTimeImmutable $doneAt): static
    {
        $this->doneAt = $doneAt;

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
