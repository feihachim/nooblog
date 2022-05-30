<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;

class PostController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepo;

    /**
     * @var CommentRepository
     */
    private $commentRepo;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = new PostRepository($doctrine);
        $this->commentRepo = new CommentRepository($doctrine);
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * Afficher la liste des derniers articles
     * 
     * @Route("/post", name="app_post")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        //$query = $this->postRepo->createQueryBuilder('p')->where(['p.isVisible' => true])->orderBy(['p.createdAt' => 'DESC'])->getQuery();
        $lastPosts = $this->postRepo->findBy(['isVisible' => true], ['createdAt' => 'DESC']);
        $lastPosts = $paginator->paginate(
            $lastPosts,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('post/index.html.twig', [
            'posts' => $lastPosts
        ]);
    }

    /**
     * Créer un nouvel article
     *
     * @param Request $request
     * @Route("/post/new",name="app_post_new")
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        if ($this->getUser())
        {
            /**
             * @var User
             */
            $user = $this->getUser();
            $profile = $user->getProfile();
            if ($profile != null)
            {
                $post->setProfile($profile);
                $form = $this->createForm(PostType::class, $post);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid())
                {
                    $this->entityManager->persist($post);
                    $this->entityManager->flush();

                    return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
                }
                return $this->renderForm('post/edit.html.twig', [
                    'form' => $form
                ]);
            }
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * Afficher un article en détail et les commentaires
     * 
     * @Route("/post/{id}",name="app_post_show",requirements={"id"="\d+"})
     */
    public function show(int $id, Request $request): Response
    {
        $post = $this->postRepo->findOneBy(['id' => $id]);
        $comments = $this->commentRepo->findBy(['post' => $post]);
        if ($this->getUser() && $post != null)
        {
            /**
             * @var User
             */
            $user = $this->getUser();
            /**
             * @var Profile
             */
            $profile = $user->getProfile();
            /**
             * @var Comment
             */
            $userComment = $this->commentRepo->findOneBy(['post' => $post, 'user' => $profile]);
            if ($userComment == null)
            {
                $userComment = new Comment();
                $userComment->setPost($post);
                $userComment->setUser($profile);
            }
            $commentForm = $this->createForm(CommentType::class, $userComment);

            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {

                $this->entityManager->persist($userComment);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_post_show', ['id' => $id]);
            }

            return $this->renderForm('post/show.html.twig', [
                'post' => $post,
                'commentForm' => $commentForm,
                'comments' => $comments
            ]);
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Modifie un article
     *
     * @param integer $id
     * @param Request $request
     * @Route("/post/edit/{id}",name="app_post_edit",requirements={"id"="\d+"})
     */
    public function edit(int $id, Request $request): Response
    {
        $post = $this->postRepo->findOneBy(['id' => $id]);
        if ($post != null)
        {
            if ($this->getUser())
            {
                /**
                 * @var User
                 */
                $user = $this->getUser();
                $profile = $user->getProfile();
                if ($profile == $post->getProfile())
                {
                    $form = $this->createForm(PostType::class, $post);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid())
                    {
                        $this->entityManager->persist($post);
                        $this->entityManager->flush();

                        $this->redirectToRoute('app_post_show', ['id' => $id]);
                    }
                    return $this->renderForm('post/edit.html.twig', [
                        'form' => $form
                    ]);
                }
            }
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * Supprime in article
     *
     * @param integer $id
     * @Route("/post/delete/{id}",name="app_post_delete",requirements={"id"="\d+"})
     */
    public function delete(int $id): Response
    {
        $post = $this->postRepo->findOneBy(['id' => $id]);
        if ($post != null && $this->getUser())
        {
            /**
             * @var User
             */
            $user = $this->getUser();
            $profile = $user->getProfile();
            if ($profile != null && $post->getProfile() == $profile)
            {
                $this->entityManager->remove($post);
                $this->entityManager->flush();
                return $this->redirectToRoute('app_profile_show', ['id' => $profile->getId()]);
            }
        }
        return $this->redirectToRoute('app_home');
    }
}
