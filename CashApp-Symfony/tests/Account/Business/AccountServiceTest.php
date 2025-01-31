<?php

declare(strict_types=1);

namespace App\Tests\Account\Business;

use App\Components\Account\Business\AccountService;
use App\Components\Account\Persistence\AccountRepository;
use App\DataTransferObjects\TransferDTO;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    private AccountRepository $accountRepository;
    private AccountService $accountService;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->accountService = new AccountService($this->accountRepository);
    }

    public function testGetBalanceReturnsCorrectBalance(): void
    {
        $transactions = [
            new TransferDTO(5, 50.0, '2024-01-01'),
            new TransferDTO(5, 20.0, '2024-01-02'),
            new TransferDTO(5, 30.0, '2024-01-03'),
        ];

        $this->accountRepository
            ->expects($this->once())
            ->method('getAllByUserID')
            ->with(5)
            ->willReturn($transactions);

        $balance = $this->accountService->getBalance(5);

        $this->assertSame(100.0, $balance);
    }

    public function testGetBalanceReturnsZero(): void
    {
        $this->accountRepository
            ->expects($this->once())
            ->method('getAllByUserID')
            ->with(0)
            ->willReturn([]);

        $balance = $this->accountService->getBalance(0);

        $this->assertSame(0.0, $balance);
    }
}
