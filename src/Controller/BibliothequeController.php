<?php

namespace App\Controller;

use App\Entity\Livres;
use App\DTO\SearchData;
use App\Entity\Emprunts;
use App\Form\SearchType;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
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

    #[Route('/bibliotheque/{livreId}', name: 'app_commentaire_emprunt', methods:['GET', 'POST'])]
    public function commentaires(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaires = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaires = $form->getData();
            $commentaires->setDateAjout(new \DateTime());
            $commentaires->setUtilisateurs($this->getUser());
            $entityManager->persist($commentaires);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_bibliotheque');
        }
    
        return $this->render('bibliotheque/commentaireEmprunt.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/bibliotheque/{livreId}', name: 'app_emprunts', methods:['GET'])]
    public function Emprunts(Request $request, LivresRepository $livreRepository, Emprunts $Emprunts , int $livreId, EntityManagerInterface $entityManager): Response
    {
        $livre = $livreRepository->find($livreId);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }
    
        $emprunt = new Emprunts();
        $emprunt->setLivres($livre);
        $emprunt->setUtilisateurs($this->getUser());
        $emprunt->setDateDemande(new \DateTime());
        $emprunt->setDateRestitutionPrevisionnelle(new \DateTime('+6 days'));
        $emprunt->setExtensionEmprunt(false);
    
        $entityManager->persist($emprunt);
        $entityManager->flush(); 
    
        $livre->setDisponibilite(false);
        $entityManager->persist($livre);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_bibliotheque');
    }

    #[Route('/bibliotheque/en_retard.html.twig', name:'app_en_retard', methods:['GER'])]
    public function livresEnRetard(EntityManagerInterface $entityManager): Response
    {
        $livresRepository = $entityManager->getRepository(Emprunts::class);
        $livresEnRetard = $livresRepository->findLivresEnRetard();
    
        return $this->render('bibliotheque/en_retard.html.twig', [
            'livres' => $livresEnRetard
        ]);
    }

    #[Route('/bibliotheque/{livreId}', name: 'app_restituer_livre', methods:['GET'])]
    public function Restituer(int $livreId, EntityManagerInterface $entityManager,  Emprunts $Emprunts): Response
    {
        // // Récupérer le livre et le dernier emprunt associé
        // $livre = $entityManager->getRepository(Livre::class)->find($livreId);
        // if (!$livre) {
        //     throw $this->createNotFoundException('Le livre avec l\'ID ' . $livreId . ' n\'existe pas.');
        // }

        // $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['livre' => $livre, 'setDateRestitutionEffective' => null]);
        // if (!$emprunt) {
        //     throw $this->createNotFoundException('Aucun emprunt actif pour ce livre.');
        // }

        // // Marquer l'emprunt comme terminé
        // $emprunt->setDateRestitutionEffective(new \DateTime());
        
        // // Marquer le livre comme disponible
        // $livre->setDisponibilite(true);
        
        // // Persister les modifications dans la base de données
        // $entityManager->persist($emprunt);
        // $entityManager->persist($livre);
        // $entityManager->flush();

        // // Rediriger vers la liste des livres, ou une autre route appropriée
        // return $this->redirectToRoute('app_bibliotheque');

        $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy([
            'livre' => $livreId,
            'restitue' => false
        ]);
    
        if (!$emprunt) {
            $this->addFlash('error', 'Ce livre n\'a pas été emprunté ou a déjà été restitué.');
            return $this->redirectToRoute('bibliotheque_index');
        }
    
        // Marquer l'emprunt comme restitué
        $emprunt->setRestitue(true);
        $emprunt->setDateRestitutionEffective(new \DateTime());
    
        // Optionnellement, mettre à jour la disponibilité du livre
        $emprunt->getLivre()->setDisponibilite(true);
    
        $entityManager->flush();
    
        $this->addFlash('success', 'Le livre a été restitué avec succès.');
        return $this->redirectToRoute('bibliotheque_index');
        

    }
}
