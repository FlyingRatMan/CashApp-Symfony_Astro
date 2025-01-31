<?php
declare(strict_types=1);

namespace App\Tests\User\Persistence\Mapper;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testCreateDTOReturnsUserDTO(): void
    {
        $mapper = new UserMapper();
        $expectedUser = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'hashed_password',
        ];

        $actualUserDTO = $mapper->createUserDTO($expectedUser);

        $this->assertInstanceOf(UserDTO::class, $actualUserDTO);
        $this->assertSame($expectedUser['name'], $actualUserDTO->name);
        $this->assertSame($expectedUser['email'], $actualUserDTO->email);
        $this->assertSame($expectedUser['password'], $actualUserDTO->password);
    }

    public function testEntityToDTOReturnsUserDTO(): void
    {
        $mapper = new UserMapper();
        $userEntity = $this->createMock(User::class);

        $userEntity->method('getId')->willReturn(1);
        $userEntity->method('getName')->willReturn('John Doe');
        $userEntity->method('getEmail')->willReturn('john.doe@example.com');
        $userEntity->method('getPassword')->willReturn('hashed_password');

        $userDTO = $mapper->entityToDTO($userEntity);

        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertSame('John Doe', $userDTO->name);
        $this->assertSame('john.doe@example.com', $userDTO->email);
        $this->assertSame('hashed_password', $userDTO->password);
    }

    public function testCreateUserDTOWithMissingData(): void
    {
        $mapper = new UserMapper();
        $data = [
            'id' => 1,
            'name' => 'John Doe',
        ];

        $this->expectException(\InvalidArgumentException::class);

        $mapper->createUserDTO($data);
    }

    public function testEntityToDTOWithInvalidUser(): void
    {
        $mapper = new UserMapper();
        $userEntity = $this->createMock(User::class);

        $userEntity->method('getId')->willReturn(0);
        $userEntity->method('getName')->willReturn('');
        $userEntity->method('getEmail')->willReturn('invalid-email');
        $userEntity->method('getPassword')->willReturn('');

        $this->expectException(\InvalidArgumentException::class);

        $mapper->entityToDTO($userEntity);
    }
}