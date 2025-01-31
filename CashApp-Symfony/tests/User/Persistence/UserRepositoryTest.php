<?php

declare(strict_types=1);

namespace App\Tests\User\Persistence;

use App\Components\User\Persistence\UserRepository;
use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use App\Tests\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetUserByEmailReturnsUser(): void
    {
        $userEmail = Config::USER_EMAIL_ONE;

        $user = $this->userRepository->getUserByEmail($userEmail);

        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertEquals($userEmail, $user->email);
    }

    public function testUpgradePasswordSuccess(): void
    {
        $userEmail = Config::USER_EMAIL_TWO;
        $newPassword = 'newPass123!';

        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        $this->assertNotNull($userEntity);

        $newHashedPassword = static::getContainer()->get('security.password_hasher')->hashPassword($userEntity, $newPassword);
        $this->userRepository->upgradePassword($userEntity, $newHashedPassword);

        $this->entityManager->refresh($userEntity);
        $this->assertEquals($newHashedPassword, $userEntity->getPassword());
    }

    public function testGetUserByEmailReturnsNull(): void
    {
        $nonExistentEmail = Config::USER_EMAIL_DOES_NOT_EXIST;

        $user = $this->userRepository->getUserByEmail($nonExistentEmail);

        $this->assertNull($user);
    }
}
