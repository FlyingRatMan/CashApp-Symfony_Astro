<?php

declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\DataTransferObjects\UserDTO;
use App\Entity\User;

class UserMapper
{
    public function createUserDTO(array $data): UserDTO
    {
        // todo is this a correct way?
        if (empty($data['id']) || empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException();
        }

        return new UserDTO($data['id'], $data['name'], $data['email'], $data['password']);
    }

    public function entityToDTO(User $userEntity): UserDTO
    {
        // todo this too
        $id = $userEntity->getId();
        $name = $userEntity->getName();
        $email = $userEntity->getEmail();
        $password = $userEntity->getPassword();

        if (empty($name) || empty($email) || empty($password) || null === $id) {
            throw new \InvalidArgumentException();
        }

        return new UserDTO(id: $id, name: $name, email: $email, password: $password);
    }
}
