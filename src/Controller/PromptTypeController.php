<?php

namespace App\Controller;

use App\Entity\PromptType;
use App\Form\PromptTypeType;
use App\Repository\PromptTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prompt/type')]
class PromptTypeController extends AbstractController
{
    #[Route('/', name: 'app_prompt_type_index', methods: ['GET'])]
    public function index(PromptTypeRepository $promptTypeRepository): Response
    {
        return $this->render('prompt_type/index.html.twig', [
            'prompt_types' => $promptTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prompt_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promptType = new PromptType();
        $form = $this->createForm(PromptTypeType::class, $promptType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promptType);
            $entityManager->flush();

            return $this->redirectToRoute('app_prompt_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prompt_type/new.html.twig', [
            'prompt_type' => $promptType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prompt_type_show', methods: ['GET'])]
    public function show(PromptType $promptType): Response
    {
        return $this->render('prompt_type/show.html.twig', [
            'prompt_type' => $promptType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prompt_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PromptType $promptType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromptTypeType::class, $promptType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prompt_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prompt_type/edit.html.twig', [
            'prompt_type' => $promptType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prompt_type_delete', methods: ['POST'])]
    public function delete(Request $request, PromptType $promptType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promptType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($promptType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prompt_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
