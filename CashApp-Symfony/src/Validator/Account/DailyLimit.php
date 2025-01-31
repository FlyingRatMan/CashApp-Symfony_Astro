<?php

declare(strict_types=1);

namespace App\Validator\Account;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class DailyLimit extends Constraint
{
    public string $message = 'Daily limit of 500 is exceeded';
    public int $limit = 500;

    public function __construct(?string $message = null)
    {
        parent::__construct($message);

        $this->message = $message ?? $this->message;
    }
}
