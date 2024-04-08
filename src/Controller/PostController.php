<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $dataPosts = $postRepo->findPublished();

        $posts = $paginator->paginate(
            $dataPosts,
            $request->query->getInt('page', 1),
            10 
        );
        return $this->render('post/index.html.twig', [
            'posts' => $posts,

        ]);
    }



    #[Route('/show/{slug}', name: 'show', methods:['GET', 'POST'])]
    public function show(string $slug, PostRepository $postRepo): Response
    {
        $post = $postRepo->findOneBy(['slug'=>$slug]);

     

   
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }





    
}
