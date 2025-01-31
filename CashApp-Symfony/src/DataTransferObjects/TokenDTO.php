<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class TokenDTO
{
    public function __construct(
        public int $id,
        public string $token,
        public string $email,
        public string $expires_at,
    ) {
    }
}
