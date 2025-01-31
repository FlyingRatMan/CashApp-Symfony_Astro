<?php

declare(strict_types=1);

namespace App\Validator\Account;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class HourlyLimitValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof HourlyLimit) {
            throw new UnexpectedTypeException($constraint, HourlyLimit::class);
        }

        $amount = (float) $value;
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $oneHourAgo = (new \DateTimeImmutable())->modify('-1 hour');

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('a.amount, a.date')
            ->from(Account::class, 'a')
            ->where('a.userId = :user')
            ->andWhere('a.date >= :oneHourAgo')
            ->setParameter('user', $user)
            ->setParameter('oneHourAgo', $oneHourAgo);

        $transactions = $queryBuilder->getQuery()->getResult();

        $hourlyLimit = 0;
        foreach ($transactions as $transaction) {
            $hourlyLimit += $transaction['amount'];
        }

        $hourlyLimit += $amount;

        if ($hourlyLimit > $constraint->limit) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ limit }}', (string) $constraint->limit)
                ->addViolation();
        }
    }
}
