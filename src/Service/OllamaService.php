<?php

declare(strict_types=1);

namespace App\Service;

use JDecool\OllamaClient\Client;
use JDecool\OllamaClient\Client\Message;
use JDecool\OllamaClient\Client\Request\ChatRequest;
use JDecool\OllamaClient\ClientBuilder;

class OllamaService {
    public function __construct(private ?Client $client = null)
    {
        $builder = new ClientBuilder();
        $this->client = $builder->create('http://ollamaphp:11434');
    }

    public function handleDutchBlogPost(string $input): string
    {
        $request = new ChatRequest('llama3', [
            new Message('user',
                "Schrijf een Nederlandse blogpost van maximaal 1500 tekens met de volgende steekwoorden; \n
                    " . $input . "." .
                "Ik wil titels en headings als markdown"),

        ]);

        return $this->client->chat($request)->message->content;
    }
}