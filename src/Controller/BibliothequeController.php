<?php

namespace App\Controller;

use DateTime;
use App\DTO\SearchData;
use App\Entity\Emprunts;
use App\Form\SearchType;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\LivresRepository;
use App\Repository\AuteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtatsLivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BibliothequeController extends AbstractController
{
    #[Route('/bibliotheque', name: 'app_bibliotheque')]
    public function index(LivresRepository $livreRepository, AuteursRepository $AuteursRepository, SearchData $data, Request $request, EtatsLivresRepository $etatsLivres): Response
    {

        $etatsLivresData = $etatsLivres->findAll();
        $auteurs = $AuteursRepository->findAll();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
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

    #[Route('/bibliotheque/commentaire/{livreId}', name: 'app_commentaire_emprunt', methods: ['GET', 'POST'])]
    public function commentaires(Request $request, EntityManagerInterface $entityManager, int $livreId): Response
    {
        $commentaires = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);
        
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour emprunter un livre.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaires = $form->getData();
            $commentaires->setDateAjout(new DateTime());
            $commentaires->setUtilisateurs($this->getUser());
            $entityManager->persist($commentaires);
            $entityManager->flush();

            return $this->redirectToRoute('app_bibliotheque');
        }

        return $this->render('bibliotheque/commentaireEmprunt.html.twig', [
            'form' => $form->createView(),
            'id' => $livreId
        ]);
    }

    #[Route('/bibliotheque/emprunt/{livreId}', name: 'app_emprunts', methods: ['GET'])]
    public function emprunts(Request $request, LivresRepository $livreRepository, EntityManagerInterface $entityManager, int $livreId): Response
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour emprunter un livre.');
        }

        // Trouver le livre par son ID
        $livre = $livreRepository->find($livreId);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }

        // Créer un nouvel emprunt
        $emprunt = new Emprunts();
        $emprunt->setLivres($livre);
        $emprunt->setUtilisateurs($user);
        $emprunt->setDateDemande(new DateTime());
        $emprunt->setDateRestitutionPrevisionnelle(new DateTime('+6 days'));
        $emprunt->setExtensionEmprunt(false);

        // Enregistrer l'emprunt dans la base de données
        $entityManager->persist($emprunt);
        $entityManager->flush();

        // Mettre à jour la disponibilité du livre
        $livre->setDisponibilite(false);
        $entityManager->persist($livre);
        $entityManager->flush();

        // Rediriger vers la page de la bibliothèque
        return $this->redirectToRoute('app_bibliotheque');
    }

    #[Route('/bibliotheque/en_retard', name: 'app_en_retard', methods: ['GET'])]
    public function livresEnRetard(EntityManagerInterface $entityManager): Response
    {
        $livresRepository = $entityManager->getRepository(Emprunts::class);
        $livresEnRetard = $livresRepository->findLivresEnRetard();

        return $this->render('bibliotheque/en_retard.html.twig', [
            'livres' => $livresEnRetard
        ]);
    }

    #[Route('/bibliotheque/restituer/{empruntId}', name: 'app_restituer', methods: ['POST'])]
        public function restituer(Request $request, EntityManagerInterface $entityManager, int $empruntId): Response
        {
            $emprunt = $entityManager->getRepository(Emprunts::class)->find($empruntId);
            if (!$emprunt || $emprunt->getUtilisateurs() != $this->getUser() || $emprunt->getDateRestitutionEffective()) {
                throw $this->createAccessDeniedException('Action non autorisée.');
            }

            $emprunt->setDateRestitutionEffective(new \DateTime());
            $emprunt->getLivres()->setDisponibilite(true);
            $entityManager->flush();

            return $this->redirectToRoute('app_bibliotheque');
        }

    // #[Route('/bibliotheque/restituer/{empruntId}', name: 'app_restituer', methods: ['POST'])]
    // public function restituer(int $empruntId, EntityManagerInterface $entityManager)
    // {
    //     $empruntRepository = $entityManager->getRepository(Emprunts::class);
    //     $emprunt = $empruntRepository->find($empruntId);
    //     if (!$emprunt) {
    //         throw $this->createNotFoundException('Aucun emprunt trouvé pour cet ID.');
    //     }
    
    //     $livre = $emprunt->getLivres(); // Supposant que votre entité Emprunts a une méthode getLivres()
    //     $livre->setDisponibilite(true);
        
    //     $emprunt->setDateRestitutionEffective(new \DateTime());
        
    //     $entityManager->flush();
    
    //     return $this->redirectToRoute('app_bibliotheque');
    // }

    public function show($livreId, LivresRepository $livreRepository, EtatsLivresRepository $EtatRepo): Response
    {
        $livreEtat = $livreRepository->find($livreId);

        if (!$livreEtat) {
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
        if (!$auteur) {
            throw $this->createNotFoundException('L\'auteur n\'existe pas');
        }

        return $this->render('bibliotheque/showAuteur.html.twig', [
            'auteur' => $auteur
        ]);
    }

    public function liste(LivresRepository $livreRepository, AuteursRepository $AuteursRepository, EtatsLivresRepository $etatsLivres): Response
    {
        return $this->render('bibliotheque/liste.html.twig', [
            'livres' => $livreRepository->findAll(),
            'auteurs' => $AuteursRepository->findAll(),
            'etatsLivres' => $etatsLivres->findAll()
        ]);
    }

    #[Route('/bibliotheque/emprunt/extend/{empruntId}', name: 'app_extend_emprunt', methods: ['POST'])]
    public function extendEmprunt(EntityManagerInterface $entityManager, int $empruntId): Response
    {
        $emprunt = $entityManager->getRepository(Emprunts::class)->find($empruntId);
        if (!$emprunt || $emprunt->isExtensionEmprunt()) {
            throw $this->createNotFoundException('Extension non autorisée ou emprunt non trouvé.');
        }

        $emprunt->setDateRestitutionPrevisionnelle($emprunt->getDateRestitutionPrevisionnelle()->modify('+6 days'));
        $emprunt->setExtensionEmprunt(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_bibliotheque');
    }
}
