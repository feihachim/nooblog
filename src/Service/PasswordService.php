<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordService
{
    /**
     * Undocumented variable.
     *
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function encodePassword(User $user, string $plainPassword): string
    {
        /**
         * @var string
         */
        $encodedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);

        return $encodedPassword;
    }
}
