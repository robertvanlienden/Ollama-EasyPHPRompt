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

        if (!$request) {
            echo "Request not found";
            return;
        }

        $request->setStatus('PROCESSING');
        $request->setPickedUpByWorkerAt(new \DateTimeImmutable());
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        $response = $this->ollamaService->handleDutchBlogPost($request->getInput());
        $response = str_replace("\n", "<br>", $this->parseMarkdown($response));

        $request->setOutput($response);
        $request->setStatus('DONE');
        $request->setDoneAt(new \DateTimeImmutable());

        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }

    private function parseMarkdown(string $input)
    {
        $parseDown = new Parsedown();

        return $parseDown->text($input);
    }
}
