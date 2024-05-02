<?php

namespace App\Controller;

use App\DTO\SearchData;
use App\Form\SearchType;
use App\Repository\LivresRepository;
use App\Repository\AuteursRepository;
use App\Form\CommentairesEmpruntsType;
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

    public function commentaireEmprunt(): Response
    {
        $commentaireEmprunt = new CommentairesEmprunts();
        $form = $this->createForm(CommentairesEmpruntsType::class, $commentaireEmprunt);
        $handleRequest = $request->request->get('commentaire');

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireEmprunt = $form->getData();
            $commentaireEmprunt->setCreateAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaireEmprunt);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_bibliotheque');
        }

        return $this->render('bibliotheque/commentaireEmprunt.html.twig', [
            'controller_name' => 'BibliothequeController',
            'form' => $form->createView(),
            
        ]);
       
    }

    public function emprunter(Request $request, LivresRepository $livreRepository): Response
    {
        $emprunt = new Emprunts();
        $emprunt->setLivres($livre);
        $emprunt->setUser($this->getUser());
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetour(new \DateTime('+6 days'));
        $emprunt->setExtension(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($emprunt);
        $entityManager->flush(); 

        $livre->setDisponible(new \DateTime('+6 days'));
        $entityManager->persist($livre);
        $entityManager->flush();

        return $this->redirectToRoute('app_bibliotheque');
    }
}
