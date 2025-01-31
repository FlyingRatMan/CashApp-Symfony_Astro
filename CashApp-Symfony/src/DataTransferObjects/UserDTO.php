<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
