<?php

namespace App\Controller;

use App\Form\ChatFormType;
use App\Service\OllamaService;
use Parsedown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OhLlamaController extends AbstractController
{
    public function __construct(private OllamaService $ollamaService)
    {
    }

    #[Route('/', name: 'app_oh_llama')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ChatFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $this->ollamaService->handleDutchBlogPost($form->getData()['data']);
            $parseDown = new Parsedown();
            $message = $parseDown->text($message);
            $message = str_replace("\n", "<br>", $message);
        }

        return $this->render('oh_llama/index.html.twig', [
            'controller_name' => 'OhLlamaController',
            'form' => $form,
            'message' => $message ?? null,
        ]);
    }
}
