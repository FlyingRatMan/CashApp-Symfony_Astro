<?php

declare(strict_types=1);

namespace App\Validator\User;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class EmailIsUnique extends Constraint
{
    public string $message = 'This email is already registered';

    public function __construct(?string $message = null)
    {
        parent::__construct();

        $this->message = $message ?? $this->message;
    }
}
