<?php

namespace App\Controller;

use App\Entity\OllamaRequest;
use App\Form\ChatFormType;
use App\Message\OllamaMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class OhLlamaController extends AbstractController
{
    public function __construct(private MessageBusInterface $bus, private EntityManagerInterface $manager)
    {
    }

    #[Route('/', name: 'app_oh_llama')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ChatFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ollamaRequest = new OllamaRequest();
            $ollamaRequest->setInput($form->getData()['data']);
            $ollamaRequest->setPromptPrefix($form->getData()['promptType']);

            $this->manager->persist($ollamaRequest);
            $this->manager->flush();

            $this->bus->dispatch(new OllamaMessage($ollamaRequest->getId()));
            $this->addFlash('success', 'Added request to queue.');
        }

        return $this->render('oh_llama/index.html.twig', [
            'form' => $form,
        ]);
    }
}
