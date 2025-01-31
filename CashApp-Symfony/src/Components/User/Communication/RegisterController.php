<?php

declare(strict_types=1);

namespace App\Components\User\Communication;

use App\Components\User\Communication\Form\Type\RegisterForm;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserMapper $mapper,
        private UserEntityManager $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Route('/register', name: 'register_page', methods: ['GET'])]
    public function renderPage(): Response
    {
        $form = $this->createForm(RegisterForm::class);

        return $this->render('register/register.html.twig', ['form' => $form]);
    }

    #[Route('/register', name: 'register_form', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegisterForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $hashedPassword = $this->passwordHasher->hashPassword(
                new User(),
                $data['password']
            );

            $newUser = [
                'id' => 1,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
            ];

            $userDTO = $this->mapper->createUserDTO($newUser);

            $this->entityManager->save($userDTO);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form,
        ]);
    }
}
