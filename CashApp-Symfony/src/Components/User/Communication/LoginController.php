<?php

namespace App\Components\User\Communication;

use App\Components\User\Persistence\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use function PHPUnit\Framework\throwException;

class LoginController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $userName = $user->getName();
        $token = 'good token';
//        bin2hex(random_bytes(32));

        $user->setToken($token);
        $user->setTokenExpiresAt(new \DateTimeImmutable('tomorrow'));

        $this->entityManager->flush();

        return $this->json([
            'user' => $userName,
            'token' => $token,
        ], Response::HTTP_OK);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(Request $request): Response {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'token' => $data['token'],
        ]);

        if (null === $user) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $user->setToken(null);
        $user->setTokenExpiresAt(null);

        $this->entityManager->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
