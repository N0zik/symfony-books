<?php
namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private CategoriesRepository $categ){}
    #[Route('/', name: 'app_home')]
    public function index(Categories $categorie): Response
    {
        return $this->render('home/index.html.twig', [    
            'categs' => $this ->categ->findAll()
        ]);
    }

    #[Route('/detail/{id}', name: 'details')]
    public function details(Categories $categ): Response
    {
        $categ =$this->categ->findOneBy(['id'=> $id]);
        dd($categ);
        return $this->render('home/index.html.twig', [    
            'categs' => $categ
        ]);
    }
}