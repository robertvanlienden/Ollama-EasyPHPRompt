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
        $ollamaRequestRepository = $this->entityManager->getRepository(OllamaRequest::class);
        $ollamaRequest = $ollamaRequestRepository->findOneBy(['id' => $message->ollamaRequestId]);

        if (!$ollamaRequest) {
            echo "Request not found";
            return;
        }

        $ollamaRequest->setStatus('PROCESSING');
        $ollamaRequest->setPickedUpByWorkerAt(new \DateTimeImmutable());
        $this->entityManager->persist($ollamaRequest);
        $this->entityManager->flush();

        $response = $this->ollamaService->handleDutchBlogPost($ollamaRequest->getInput(), $ollamaRequest->getPromptPrefix(), $ollamaRequest->getPromptSuffix());
        $response = str_replace("\n", "<br>", $this->parseMarkdown($response));

        $ollamaRequest->setOutput($response);
        $ollamaRequest->setStatus('DONE');
        $ollamaRequest->setDoneAt(new \DateTimeImmutable());

        $this->entityManager->persist($ollamaRequest);
        $this->entityManager->flush();
    }

    private function parseMarkdown(string $input)
    {
        $parseDown = new Parsedown();

        return $parseDown->text($input);
    }
}
