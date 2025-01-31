<?php
declare(strict_types=1);

namespace App\Tests\User\Persistence;

use App\Components\User\Persistence\ResetPasswordRequestRepository;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResetPasswordRequestRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ResetPasswordRequestRepository $repository;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $managerRegistry = $this->createMock(ManagerRegistry::class);

        $managerRegistry
            ->method('getManagerForClass')
            ->willReturn($this->entityManager);

        $this->repository = new ResetPasswordRequestRepository($managerRegistry);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testCreateResetPasswordRequest(): void
    {
        $user = $this->createMock(User::class);
        $expiresAt = new \DateTimeImmutable('+1 hour');
        $selector = 'random-selector-string';
        $hashedToken = 'hashed-token-example';

        $resetPasswordRequest = $this->repository->createResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);

        $this->assertInstanceOf(ResetPasswordRequest::class, $resetPasswordRequest);

        $this->assertEquals($user, $resetPasswordRequest->getUser());
        $this->assertEquals($expiresAt, $resetPasswordRequest->getExpiresAt());
        $this->assertEquals($hashedToken, $resetPasswordRequest->getHashedToken());
    }
}
