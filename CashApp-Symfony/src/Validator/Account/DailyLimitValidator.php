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

class DailyLimitValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DailyLimit) {
            throw new UnexpectedTypeException($constraint, DailyLimit::class);
        }

        $amount = (float) $value;
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $startOfDay = (new \DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s');
        $endOfDay = (new \DateTimeImmutable('tomorrow midnight'))->format('Y-m-d H:i:s');

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('a.amount, a.date')
            ->from(Account::class, 'a')
            ->where('a.userId = :user')
            ->andWhere('a.date BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('user', $user)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay);

        $transactions = $queryBuilder->getQuery()->getResult();

        $dailyLimit = 0;
        foreach ($transactions as $transaction) {
            $dailyLimit += $transaction['amount'];
        }

        $dailyLimit += $amount;

        if ($dailyLimit > $constraint->limit) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ limit }}', (string) $constraint->limit)
                ->addViolation();
        }
    }
}
