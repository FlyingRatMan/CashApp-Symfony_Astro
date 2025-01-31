<?php

namespace App\Components\User\Persistence;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private UserMapper $userMapper,
    ) {
        parent::__construct($registry, User::class);
    }

    public function getUserByEmail(string $email): ?UserDTO
    {
        $userEntity = $this->findOneBy(['email' => $email]);

        if (null !== $userEntity) {
            return $this->userMapper->entityToDTO($userEntity);
        }

        return null;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        $user->setPassword($newHashedPassword);

        $this->getEntityManager()->flush();
    }
}
