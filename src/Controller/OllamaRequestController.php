<?php

namespace App\Controller;

use App\Entity\OllamaRequest;
use App\Form\OllamaRequestType;
use App\Repository\OllamaRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ollama/request')]
class OllamaRequestController extends AbstractController
{
    #[Route('/', name: 'app_ollama_request_index', methods: ['GET'])]
    public function index(OllamaRequestRepository $ollamaRequestRepository): Response
    {
        return $this->render('ollama_request/index.html.twig', [
            'ollama_requests' => $ollamaRequestRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_ollama_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ollamaRequest = new OllamaRequest();
        $form = $this->createForm(OllamaRequestType::class, $ollamaRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ollamaRequest);
            $entityManager->flush();

            return $this->redirectToRoute('app_ollama_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ollama_request/new.html.twig', [
            'ollama_request' => $ollamaRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ollama_request_show', methods: ['GET'])]
    public function show(OllamaRequest $ollamaRequest): Response
    {
        return $this->render('ollama_request/show.html.twig', [
            'ollama_request' => $ollamaRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ollama_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OllamaRequest $ollamaRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OllamaRequestType::class, $ollamaRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ollama_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ollama_request/edit.html.twig', [
            'ollama_request' => $ollamaRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ollama_request_delete', methods: ['POST'])]
    public function delete(Request $request, OllamaRequest $ollamaRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ollamaRequest->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($ollamaRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ollama_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
