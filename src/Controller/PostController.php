<?php

namespace App\Controller;

use App\DTO\SearchData;
use App\Form\SearchType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepo,  Request $request, SearchData $data ): Response
    {
        $page = $request->query->getInt('page', 1);
        $posts = $postRepo->findPublished($page);
        // générer le formulaire de recherche
        $form = $this->createForm(SearchType::class, $data);
        //verifie si il y a une requête
        $form->handleRequest($request);
        //si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
                dd( $data -> query );

            //récupérer les données du formulaire
            // $data = $form->getData();
            //rediriger vers la page de recherche
            // return $this->redirectToRoute('search', ['query' => $data['query']]);
        }


        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'formView' => $form->createView(),

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
