<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['name' => Config::USER_NAME_ONE, 'email' => Config::USER_EMAIL_ONE, 'password' => Config::USER_PASSWORD],
            ['name' => Config::USER_NAME_TWO, 'email' => Config::USER_EMAIL_TWO, 'password' => Config::USER_PASSWORD],
            ['name' => Config::USER_NAME_THREE, 'email' => Config::USER_EMAIL_THREE, 'password' => Config::USER_PASSWORD],
        ];

        foreach ($users as $i => $userData) {
            $user = new User();
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            $this->addReference("user_{$i}", $user);
        }

        $manager->flush();
    }
}
