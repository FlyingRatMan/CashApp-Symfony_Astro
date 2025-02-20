<?php

declare(strict_types=1);

namespace App\Components\User\Communication;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserMapper                  $mapper,
        private UserEntityManager           $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface          $validator,
    ) {}

    #[Route('/api/register', name: 'register_form', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $userDTO = $this->mapper->createUserDTO([
            'id' => 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $violations = $this->validator->validate($userDTO);
        if (count($violations) > 0) {
            return $this->json([
                'status' => 401,
                'message' => $violations[0]->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(new User(), $data['password']);

        $userDTO = $this->mapper->createUserDTO([
            'id' => 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
        ]);

        $this->entityManager->save($userDTO);

        return $this->json([
            'status' => 200,
            'message' => 'Symfony: User registered successfully.',
        ], Response::HTTP_OK);
    }
}
