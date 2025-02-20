<?php

namespace App\Components\User\Communication;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface       $entityManager,
    ) {}

    #[Route('/api/reset-password', name: 'api_reset_password', methods: ['POST'])]
    public function apiRequestResetPassword(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse([
                'message' => 'Invalid email address.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse([
                'message' => 'If your email exists in our system, you will receive a reset link.',
            ], Response::HTTP_OK);
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface) {
            return new JsonResponse([
                'message' => 'Error generating reset token.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $emailBody = '
    <h1>Hi!</h1>
    <p>To reset your password, please visit the following link:</p>
    <p><a href="http://localhost:4321/reset-password/' . $resetToken->getToken() . '">Reset password</a></p>
    <p>This link will expire in ' . $resetToken->getExpirationMessageKey() . '.</p>
    <p>Cheers!</p>
';

        $email = (new TemplatedEmail())
            ->from(new Address('resetPassword@cash.app.com'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->html($emailBody);

        $mailer->send($email);

        return new JsonResponse([
            'message' => 'Password reset link sent.',
        ], Response::HTTP_OK);
    }

    #[Route('/api/reset-password/{token}', name: 'api_reset_password_confirm', methods: ['POST'])]
    public function resetPasswordConfirm(string $token, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['password']) || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/", $data['password'])) {
            return new JsonResponse([
                'message' => 'Password must be at least 6 characters long and include at least one uppercase letter, one 
                lowercase letter, one number, and one special character.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);

        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Invalid or expired token.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->resetPasswordHelper->removeResetRequest($token);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Password successfully reset.'
        ], Response::HTTP_OK);
    }
}
