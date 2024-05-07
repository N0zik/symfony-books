<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AbonnementsController extends AbstractController
{
    #[Route('/abonnements', name: 'app_abonnements')]
    public function index(): Response
    {
        return $this->render('abonnements/index.html.twig', [
            'controller_name' => 'AbonnementsController',
        ]);
    }
}
