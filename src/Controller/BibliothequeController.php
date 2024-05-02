<?php

namespace App\Controller;

use App\Entity\Emprunts;
use App\Entity\Livres;
use App\DTO\SearchData;
use App\Form\SearchType;
use App\Repository\LivresRepository;
use App\Repository\AuteursRepository;
use App\Form\CommentairesEmpruntsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtatsLivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BibliothequeController extends AbstractController
{
    #[Route('/bibliotheque', name: 'app_bibliotheque',)]
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

    public function commentaireEmprunt(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaireEmprunt = new CommentairesEmprunts();
        $form = $this->createForm(CommentairesEmpruntsType::class, $commentaireEmprunt);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireEmprunt = $form->getData();
            $commentaireEmprunt->setCreateAt(new \DateTime());
            $commentaireEmprunt->setUtilisateurs($this->getUser());
            $entityManager->persist($commentaireEmprunt);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_bibliotheque');
        }
    
        return $this->render('bibliotheque/commentaireEmprunt.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/bibliotheque/emprunts/{livreId}', name: 'app_emprunts', methods:['GET'])]

    public function Emprunts(Request $request, LivresRepository $livreRepository, Emprunts $Emprunts , int $livreId): Response
    {
        $livre = $livreRepository->find($livreId);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }
    
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

    public function livresEnRetard(EntityManagerInterface $entityManager): Response
    {
        $livresRepository = $entityManager->getRepository(Emprunts::class);
        $livresEnRetard = $livresRepository->findLivresEnRetard();
    
        return $this->render('bibliotheque/en_retard.html.twig', [
            'livres' => $livresEnRetard
        ]);
    }
    
}
