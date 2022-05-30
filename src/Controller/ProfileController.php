<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * Undocumented variable
     *
     * @var ProfileRepository
     */
    private $profileRepo;

    /**
     * Undocumented variable
     *
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->profileRepo = new ProfileRepository($doctrine);
        $this->entityManager = $doctrine->getManager();
    }
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/profile/{id}",name="app_profile_show")
     */
    public function show(int $id): Response
    {
        $profile = $this->profileRepo->findOneBy(['id' => $id]);
        if ($profile != null)
        {
            return $this->render('profile/show.html.twig', [
                'profile' => $profile
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * Undocumented function
     *
     * @Route("/profile/new",name="app_profile_new")
     */
    public function new(): Response
    {
        return $this->render('profile/edit.html.twig');
    }

    /**
     * @Route("/profile/edit/{id}",name="app_profile_edit")
     */
    public function edit(): Response
    {
        return $this->render('profile/edit.html.twig');
    }
}
