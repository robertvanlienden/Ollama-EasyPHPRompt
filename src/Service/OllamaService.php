<?php

declare(strict_types=1);

namespace App\Service;

use JDecool\OllamaClient\Client;
use JDecool\OllamaClient\Client\Message;
use JDecool\OllamaClient\Client\Request\ChatRequest;
use JDecool\OllamaClient\ClientBuilder;

class OllamaService {
    public function __construct(private ?Client $client = null, private string $model)
    {
        $builder = new ClientBuilder();
        $this->client = $builder->create('http://ollamaphp:11434');
    }

    public function handleDutchBlogPost(string $input, string $promptPrefix, string $promptSuffix): string
    {
        $request = new ChatRequest($this->model, [
            new Message('user',
                $promptPrefix . "\n"
                     . $input . ".\n\n" .
                $promptSuffix . "\n\n" .
                "I want titles and headings as markdown format. Just only output information, no extra questions."),
        ]);

        $message = '';

        foreach ($this->client->chatStream($request) as $chunk) {
            $message .= $chunk->message->content;
        }

        return $message;
    }

    public function getAvailableModels()
    {
        return $this->client->list()->models;
    }
}