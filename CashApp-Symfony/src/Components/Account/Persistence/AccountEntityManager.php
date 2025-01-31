<?php

declare(strict_types=1);

namespace App\Components\Account\Persistence;

use App\DataTransferObjects\TransferDTO;
use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AccountEntityManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function add(User $user, TransferDTO $transferDTO): void
    {
        $transactionEntity = new Account();
        $transactionEntity->setUserId($user);
        $transactionEntity->setAmount($transferDTO->amount);
        $transactionEntity->setDate($transferDTO->date);

        $user->addTransaction($transactionEntity);

        $this->entityManager->persist($transactionEntity);
        $this->entityManager->flush();
    }
}
