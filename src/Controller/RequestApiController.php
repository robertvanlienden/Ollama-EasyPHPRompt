<?php

namespace App\Controller;

use App\Entity\OllamaRequest;
use App\Entity\PromptType;
use App\Message\OllamaMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class RequestApiController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private MessageBusInterface $bus)
    {
    }

    public function __invoke(PromptType $promptType, Request $request)
    {
        $data = $request->getPayload();

        $ollamaRequest = new OllamaRequest();
        $ollamaRequest->setInput($data->get('data'));
        $ollamaRequest->setPromptPrefix($promptType->getPromptPrefix());
        $ollamaRequest->setPromptSuffix($promptType->getPromptSuffix());

        $this->manager->persist($ollamaRequest);
        $this->manager->flush();

        $this->bus->dispatch(new OllamaMessage($ollamaRequest->getId()));

        return $this->json([
            'ollamaRequest' => $ollamaRequest,
        ]);
    }
}
