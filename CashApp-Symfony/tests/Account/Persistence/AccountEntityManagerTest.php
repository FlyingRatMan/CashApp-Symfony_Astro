<?php

declare(strict_types=1);

namespace App\Tests\Account\Persistence;

use App\Components\Account\Persistence\AccountEntityManager;
use App\DataTransferObjects\TransferDTO;
use App\Entity\Account;
use App\Entity\User;
use App\Tests\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccountEntityManagerTest extends KernelTestCase
{
    private AccountEntityManager $accountEntityManager;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->accountEntityManager = $container->get(AccountEntityManager::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testAddSuccessful(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => Config::USER_EMAIL_ONE,
        ]);
        $userId = $user->getId();
        $this->assertNotNull($userId);

        $transferDTO = new TransferDTO($userId, 10.0, '2024-08-22');

        $this->accountEntityManager->add($user, $transferDTO);
        $accountEntity = $this->entityManager->getRepository(Account::class)->findOneBy(['userId' => $transferDTO->id]);

        $this->assertNotNull($accountEntity);
        $this->assertSame($transferDTO->id, $accountEntity->getUserId()->getId());
        $this->assertSame($transferDTO->amount, $accountEntity->getAmount());
        $this->assertSame($transferDTO->date, $accountEntity->getDate());
    }
}
