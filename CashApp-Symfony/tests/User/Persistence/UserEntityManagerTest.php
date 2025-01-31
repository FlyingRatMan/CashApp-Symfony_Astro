<?php

declare(strict_types=1);

namespace App\Tests\User\Persistence;

use App\Components\User\Persistence\UserEntityManager;
use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserEntityManagerTest extends KernelTestCase
{
    private UserEntityManager $userEntityManager;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->userEntityManager = $container->get(UserEntityManager::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testSaveNewUserSuccessful(): void
    {
        $userDTO = new UserDTO(
            id: 1,
            name: 'John Doe',
            email: 'john.doe@example.com',
            password: 'Securepassword1!'
        );

        $this->userEntityManager->save($userDTO);

        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'john.doe@example.com']);

        $this->assertNotNull($userEntity);
        $this->assertSame('John Doe', $userEntity->getName());
        $this->assertSame('john.doe@example.com', $userEntity->getEmail());
        $this->assertSame('Securepassword1!', $userEntity->getPassword());
    }

    public function testSaveFromInvalidDTO(): void
    {
        $userDTO = new UserDTO(
            1,
            'Existing User',
            'john.doe@example.com',
            'password'
        );

        $this->expectException(\InvalidArgumentException::class);

        $this->userEntityManager->save($userDTO);
    }
}
