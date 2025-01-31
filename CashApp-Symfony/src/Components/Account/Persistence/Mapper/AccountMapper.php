<?php

declare(strict_types=1);

namespace App\Components\Account\Persistence\Mapper;

use App\DataTransferObjects\TransferDTO;
use App\Entity\Account;

class AccountMapper
{
    public function createTransferDTO(array $data): TransferDTO
    {
        // todo should i copy the validation method from user mapper?
        if (empty($data['id']) || empty($data['amount']) || empty($data['date'])) {
            throw new \InvalidArgumentException();
        }

        return new TransferDTO($data['id'], $data['amount'], $data['date']);
    }

    public function entityToDTO(Account $accountEntity): TransferDTO
    {
        $id = $accountEntity->getId();
        $amount = $accountEntity->getAmount();
        $date = $accountEntity->getDate();

        if (empty($amount) || empty($date) || null === $id) {
            throw new \InvalidArgumentException();
        }

        return new TransferDTO($id, $amount, $date);
    }
}
