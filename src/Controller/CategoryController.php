<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{
    /**
     * Undocumented variable
     *
     * @var PostRepository
     */
    private $postRepo;

    /**
     * Undocumented variable
     *
     * @var CategoryRepository
     */
    private $categoryRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = new PostRepository($doctrine);
        $this->categoryRepo = new CategoryRepository($doctrine);
    }

    /**
     * @Route("/category", name="app_category")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepo->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @Route("/category/{id}",name="app_category_show",requirements={"id"="\d+"})
     */
    public function show(int $id = null): Response
    {
        if ($id == null)
        {
            $posts = $this->postRepo->findBy(['isVisible' => true, 'category' => null], ['createdAt' => 'DESC']);
        }
        else
        {
            $category = $this->categoryRepo->findOneBy(['id' => $id]);
            $posts = $this->postRepo->findBy(['isVisible' => true, 'category' => $category]);
        }
        return $this->render('category/show.html.twig', [
            'posts' => $posts
        ]);
    }
}
