<?php

declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(UserDTO $userDTO): void
    {
        $userEntity = new User();
        $userEntity->setName($userDTO->name);
        $userEntity->setEmail($userDTO->email);
        $userEntity->setPassword($userDTO->password);

        try {
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }
}
