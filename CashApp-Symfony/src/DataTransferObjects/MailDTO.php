<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class MailDTO
{
    public function __construct(
        public string $from,
        public string $to,
        public string $subject,
        public string $html,
    ) {
    }
}
