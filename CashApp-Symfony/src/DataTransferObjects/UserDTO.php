<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Validator\User\EmailIsUnique;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public function __construct(
        public int $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Email()]
        #[EmailIsUnique()]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Regex([
            'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/",
            'message' => 'Password is not valid',
        ])]
        public string $password,
    ) {
    }
}
