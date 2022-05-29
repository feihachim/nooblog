<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\User;
use App\Service\PasswordService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * Undocumented variable.
     *
     * @var PasswordService
     */
    private $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 6; ++$i)
        {
            $user = new User();
            $password = $this->passwordService->encodePassword($user, 'test123');
            $user->setEmail("abc$i@oklm.com");
            $user->setPassword($password);
            if ($i % 2 == 0)
            {
                $user->setRoles(["ROLE_ADMIN"]);
            }

            $profile = new Profile();
            $profile->setPseudo("abc$i");
            $user->setProfile($profile);

            $manager->persist($profile);
            $manager->persist($user);

            for ($j = 0; $j < 5; ++$j)
            {
                $post = new Post();
                $post->setTitle("Titre n°$j");
                $post->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.contenu du texte numéro $j");
                $post->setProfile($profile);

                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}
