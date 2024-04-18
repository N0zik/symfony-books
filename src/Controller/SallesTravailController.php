<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SallesTravailController extends AbstractController
{
    #[Route('/salles/travail', name: 'app_salles_travail')]
    public function index(): Response
    {
        return $this->render('salles_travail/index.html.twig', [
            'controller_name' => 'SallesTravailController',
        ]);
    }
}

// Path: templates/salles_travail/index.html.twig


