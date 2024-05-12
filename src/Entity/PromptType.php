<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\RequestApiController;
use App\Repository\PromptTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/prompt_types/request/{id}',
            controller: RequestApiController::class,
            openapiContext: [
                'responses' => [
                    '200' => [
                        'description' => 'Successful response with ollama request',
                        'content' => [
                            'application/ld+json' => [
                                'example' => [
                                    'ollamaRequest' => [
                                        "id" => 1,
                                        "input" => "Data for your prompt",
                                        "output" => null,
                                        "status" => "NEW",
                                        "pickedUpByWorkerAt" => null,
                                        "createdAt" => "2024-05-13T00:08:45+02:00",
                                        "doneAt" => null,
                                        "promptPrefix" => "Prompt prefix",
                                        "promptSuffix" => "Prompt suffix"
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            openapi: new Model\Operation(
                summary: 'Create new Ollama Request',
                description: 'Create new Ollama Request',
                requestBody: new Model\RequestBody(
                    description: 'Create new Ollama Request',
                    content: new \ArrayObject([
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'data' => ['type' => 'string'],
                                ]
                            ],
                            'example' => [
                                'data' => 'Data for your prompt',
                            ]
                        ]
                    ])
                ),
            ),
            name: 'create_request_with_prompt_type'
        ),
    ]
)]
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
