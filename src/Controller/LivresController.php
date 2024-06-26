<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Entity\Emprunts;
use App\Repository\EmpruntsRepository;
use App\Form\LivresType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/livres')]
class LivresController extends AbstractController
{
    #[Route('/', name: 'app_livres_index', methods: ['GET'])]
    public function index(LivresRepository $livresRepository): Response
    {
        return $this->render('livres/index.html.twig', [
            'livres' => $livresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livres();
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livres/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livres_show', methods: ['GET'])]
    public function show(Livres $livre): Response
    {
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livres $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livres/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livres_delete', methods: ['POST'])]
    public function delete(Request $request, Livres $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livres_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('en_retard', name: 'app_en_retard', methods: ['GET'])]
    public function livresEnRetard(EntityManagerInterface $entityManager): Response
    {
        $empruntsRepository = $entityManager->getRepository(Emprunts::class);
        $empruntsEnRetard = $empruntsRepository->findLivresEnRetard();

        return $this->render('livres/en_retard.html.twig', [
            'empruntsEnRetard' => $empruntsEnRetard
        ]);
    }
}