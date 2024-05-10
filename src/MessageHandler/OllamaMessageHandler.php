<?php

namespace App\MessageHandler;

use App\Entity\OllamaRequest;
use App\Message\OllamaMessage;
use App\Service\OllamaService;
use Doctrine\ORM\EntityManagerInterface;
use Parsedown;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OllamaMessageHandler
{
    public function __construct(private OllamaService $ollamaService, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(OllamaMessage $message)
    {
        $repository = $this->entityManager->getRepository(OllamaRequest::class);
        $request = $repository->findOneBy(['id' => $message->ollamaRequestId]);

        $request->setStatus('PROCESSING');
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        if (!$request) {
            echo "Request not found";
            return;
        }

        $response = $this->ollamaService->handleDutchBlogPost($request->getInput());
        $parseDown = new Parsedown();
        $response = $parseDown->text($response);
        $response = str_replace("\n", "<br>", $response);

        $request->setOutput($response);
        $request->setStatus('DONE');

        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}
