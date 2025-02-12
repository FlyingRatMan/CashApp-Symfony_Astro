<?php

namespace App\Components\User\Communication;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $token = 'tokenOfTrust';
        $userName = $user->getName();

        return $this->json([
            'user' => $userName,
            'token' => $token,
        ], Response::HTTP_OK);
    }

    #[Route('/logout', name: 'api_logout', methods: ['GET'])]
    public function logout(Request $request): Response {
        return $this->json([], Response::HTTP_OK);
    }
}
