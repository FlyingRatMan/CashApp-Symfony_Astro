<?php

declare(strict_types=1);

namespace App\Components\Account\Business;

use App\Components\Account\Persistence\AccountRepository;

class AccountService
{
    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function getBalance(int $userId): float
    {
        $balance = 0;
        $transactions = $this->accountRepository->getAllByUserID($userId);

        foreach ($transactions as $transaction) {
            $balance += $transaction->amount;
        }

        return $balance;
    }
}
