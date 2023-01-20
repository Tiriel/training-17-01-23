<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adrien = new User();
        $adrien->setUsername('adrien');
        $adrien->setPassword(
            $this->passwordHasher->hashPassword($adrien, 'adrien')
        );
        $adrien->setRoles(['ROLE_ADMIN']);

        $manager->persist($adrien);

        $john = new User();
        $john->setUsername('john');
        $john->setPassword(
            $this->passwordHasher->hashPassword($john, 'john')
        );
        $john->setRoles(['ROLE_ADMIN']);

        $manager->persist($john);

        $manager->flush();
    }
}
