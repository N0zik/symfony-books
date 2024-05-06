<?php

namespace App\Controller;

use App\DTO\SearchData;
use App\Entity\Commentaires;
use App\Entity\Emprunts;
use App\Form\CommentairesType;
use App\Form\SearchType;
use App\Repository\AuteursRepository;
use App\Repository\EtatsLivresRepository;
use App\Repository\LivresRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function commentaires(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaires = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);

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
        ]);
    }

    #[Route('/bibliotheque/{livreId}', name: 'app_emprunts', methods: ['GET'])]
    public function Emprunts(Request $request, LivresRepository $livreRepository, Emprunts $Emprunts, int $livreId, EntityManagerInterface $entityManager): Response
    {
        $livre = $livreRepository->find($livreId);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }

        $emprunt = new Emprunts();
        $emprunt->setLivres($livre);
        $emprunt->setUtilisateurs($this->getUser());
        $emprunt->setDateDemande(new DateTime());
        $emprunt->setDateRestitutionPrevisionnelle(new DateTime('+6 days'));
        $emprunt->setExtensionEmprunt(false);

        $entityManager->persist($emprunt);
        $entityManager->flush();

        $livre->setDisponibilite(false);
        $entityManager->persist($livre);
        $entityManager->flush();

        return $this->redirectToRoute('app_bibliotheque');
    }

    #[Route('/bibliotheque/en_retard.html.twig', name: 'app_en_retard', methods: ['GET'])]
    public function livresEnRetard(EntityManagerInterface $entityManager): Response
    {
        $livresRepository = $entityManager->getRepository(Emprunts::class);
        $livresEnRetard = $livresRepository->findLivresEnRetard();

        return $this->render('bibliotheque/en_retard.html.twig', [
            'livres' => $livresEnRetard
        ]);
    }

    #[Route('/bibliotheque/{livreId}', name: 'app_restituer', methods: ['GET'])]
    public function restituer(int $id, EntityManagerInterface $entityManager)
    {
    $emprunt = $entityManager->getRepository(Emprunts::class)->find($id);
    if (!$emprunt) {
        throw $this->createNotFoundException('Aucun emprunt trouvé pour cet id');
    }

    $emprunt->setRestitue(true);
    $emprunt->setDateRestitutionEffective(new \DateTime());

    $entityManager->flush();

    // Redirect to a route after setting the return
    return $this->redirectToRoute('app_livre_list'); // Change this to wherever you need to redirect
    }

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
}
