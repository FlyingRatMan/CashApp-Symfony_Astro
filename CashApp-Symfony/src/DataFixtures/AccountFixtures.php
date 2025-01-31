<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 2; ++$i) {
            $user = $this->getReference("user_{$i}", User::class);

            for ($j = 0; $j < 5; ++$j) {
                $accountEntity = new Account();
                $accountEntity->setUserId($user);
                $accountEntity->setAmount(10.0);
                $accountEntity->setDate('2024-08-22');

                $manager->persist($accountEntity);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
