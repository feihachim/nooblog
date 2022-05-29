<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * Undocumented variable
     *
     * @var PostRepository
     */
    private $postRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = new PostRepository($doctrine);
    }

    /**
     * @Route("/post", name="app_post")
     */
    public function index(): Response
    {
        $lastPosts = $this->postRepo->findBy(['isVisible' => true], ['createdAt' => 'DESC']);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $lastPosts
        ]);
    }

    /**
     * @Route("/post/{id}",name="app_post_show")
     */
    public function show(): Response
    {
        return $this->render('');
    }
}
