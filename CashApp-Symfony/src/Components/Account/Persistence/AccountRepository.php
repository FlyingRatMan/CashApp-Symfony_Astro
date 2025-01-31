<?php

namespace App\Components\Account\Persistence;

use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly AccountMapper $accountMapper,
    ) {
        parent::__construct($registry, Account::class);
    }

    public function getAllByUserID(int $userId): array
    {
        $transactions = $this->findBy(['userId' => $userId]);

        if (empty($transactions)) {
            return [];
        }

        $listOfTransactions = [];
        foreach ($transactions as $transaction) {
            $accountDTO = $this->accountMapper->createTransferDTO(
                [
                    'id' => $transaction->getId(),
                    'amount' => $transaction->getAmount(),
                    'date' => $transaction->getDate(),
                ]);
            $listOfTransactions[] = $accountDTO;
        }

        return $listOfTransactions;
    }
}
