<?php

namespace App\Controller;

use App\DTO\SearchData;
use App\Form\SearchType;
use App\Repository\LivresRepository;
use App\Repository\AuteursRepository;
use App\Repository\EtatsLivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BibliothequeController extends AbstractController
{
    #[Route('/bibliotheque', name: 'app_bibliotheque')]
    public function index(LivresRepository $livreRepository, AuteursRepository $AuteursRepository, SearchData $data, Request $request, EtatsLivresRepository $etatsLivres ): Response
    {
       
        $etatsLivresData = $etatsLivres->findAll();
        $auteurs = $AuteursRepository->findAll();
        $form = $this->createForm(SearchType::class, $data);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $livres = $livreRepository->search($data);
          
            return $this->render('bibliotheque/index.html.twig', [
                'controller_name' => 'BibliothequeController',
                'livres' => $livres,
                'auteurs' => $auteurs,
                'form' => $form->createView(),
                'etatsLivres' => $etatsLivresData
            ]);
        } 
       

        return $this->render('bibliotheque/index.html.twig', [
            'controller_name' => 'BibliothequeController',
            'livres' => $livreRepository->findAll(),
            'auteurs' => $auteurs,
            'form' => $form->createView(),
            'etatsLivres' => $etatsLivresData
        ]);
    }

    public function show($livreId, LivresRepository $livreRepository, EtatsLivresRepository $EtatRepo): Response
    {
        $livreEtat = $livreRepository->find($livreId);

        if(!$livreEtat){
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        $etatsLivres = $livreEtat->getEtatsLivres();

        return $this->render('bibliotheque/show.html.twig', [
            'livreEtat' => $livreEtat
        ]);
    }

    public function showAuteur($auteurId, AuteursRepository $auteurRepository): Response
    {
        $auteur = $auteurRepository->find($auteurId);
        if(!$auteur){
            throw $this->createNotFoundException('L\'auteur n\'existe pas');
        }

        return $this->render('bibliotheque/showAuteur.html.twig', [
            'auteur' => $auteur
        ]);
    }
    public function liste (LivresRepository $livreRepository, AuteursRepository $AuteursRepository, EtatsLivresRepository $etatsLivres): Response
    {
        return $this->render('bibliotheque/liste.html.twig', [
            'livres' => $livreRepository->findAll(),
            'auteurs' => $AuteursRepository->findAll(),
            'etatsLivres' => $etatsLivres->findAll()
        ]);
    }
}
