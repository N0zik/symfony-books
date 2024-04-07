<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findPublished();
   
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
