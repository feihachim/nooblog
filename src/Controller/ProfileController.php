<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
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
    public function show(): Response
    {
        return $this->render('');
    }

    /**
     * @Route("/profile/edit/{id}",name="app_profile_edit")
     */
    public function edit(): Response
    {
        return $this->render('');
    }
}
