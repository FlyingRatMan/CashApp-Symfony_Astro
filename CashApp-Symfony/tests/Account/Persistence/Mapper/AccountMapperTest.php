<?php

declare(strict_types=1);

namespace App\Tests\Account\Persistence\Mapper;

use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\DataTransferObjects\TransferDTO;
use App\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountMapperTest extends TestCase
{
    public function testCreateDTOReturnsTransferDTO(): void
    {
        $mapper = new AccountMapper();
        $expectedTransfer = [
            'id' => 1,
            'amount' => 10.0,
            'date' => '2024-08-22 10:29:56',
        ];

        $transferDTO = $mapper->createTransferDTO($expectedTransfer);

        $this->assertSame($expectedTransfer['amount'], $transferDTO->amount);
        $this->assertSame($expectedTransfer['date'], $transferDTO->date);
    }

    public function testEntityToDTOReturnsTransferDTO(): void
    {
        $mapper = new AccountMapper();
        $accountEntity = $this->createMock(Account::class);

        $accountEntity->method('getId')->willReturn(1);
        $accountEntity->method('getAmount')->willReturn(10.0);
        $accountEntity->method('getDate')->willReturn('2024-08-22 10:29:56');

        $transferDTO = $mapper->entityToDTO($accountEntity);

        $this->assertInstanceOf(TransferDTO::class, $transferDTO);
        $this->assertSame(10.0, $transferDTO->amount);
        $this->assertSame('2024-08-22 10:29:56', $transferDTO->date);
    }

    public function testCreateTransferDTOWithMissingData(): void
    {
        $mapper = new AccountMapper();
        $data = [
            'id' => 1,
            'amount' => 10.0,
        ];

        $this->expectException(\InvalidArgumentException::class);

        $mapper->createTransferDTO($data);
    }

    public function testEntityToDTOWithInvalidUser(): void
    {
        $mapper = new AccountMapper();
        $accountEntity = $this->createMock(Account::class);

        $accountEntity->method('getId')->willReturn(1);
        $accountEntity->method('getAmount')->willReturn(null);
        $accountEntity->method('getDate')->willReturn('');

        $this->expectException(\InvalidArgumentException::class);

        $mapper->entityToDTO($accountEntity);
    }
}
