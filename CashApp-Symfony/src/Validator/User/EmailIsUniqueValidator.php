<?php

declare(strict_types=1);

namespace App\Validator\User;

use App\Components\User\Persistence\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailIsUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private UserRepository $repository,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmailIsUnique) {
            throw new UnexpectedTypeException($constraint, EmailIsUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $existingUser = $this->repository->getUserByEmail($value);

        if (null !== $existingUser) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
