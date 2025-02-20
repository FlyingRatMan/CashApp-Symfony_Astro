<?php

declare(strict_types=1);

namespace App\Components\Account\Communication;

use App\Components\Account\Business\AccountService;
use App\Components\Account\Communication\Form\AccountForm;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccountController extends AbstractController
{
    public function __construct(
        private AccountEntityManager $entityManager,
        private AccountMapper $mapper,
        private AccountService $accountService,
        private Security $security,
        private FormFactoryInterface $formFactory,
    ) {
    }

    #[Route('/api/account', methods: ['POST'])]
    public function index(Request $request, LoggerInterface $log): JsonResponse
    {
        $loggedUser = $this->security->getUser();
        if (!$loggedUser instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR) ?? [];

        $form = $this->formFactory->create(AccountForm::class);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $log->debug($form->getErrors(true));
            return new JsonResponse([
                'errors' => $this->accountService->getFormErrors($form),
            ], 400);
        }

        $dateStamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

        $transferDTO = $this->mapper->createTransferDTO([
            'id' => 1,
            'amount' => (double)$data['amount'],
            'date' => $dateStamp,
        ]);

        $this->entityManager->add($loggedUser, $transferDTO);

        return new JsonResponse([
            'message' => 'Transfer was successful.',
            'balance' => $this->accountService->getBalance($loggedUser->getId()),
        ]);
    }

    #[Route('/api/balance', methods: ['GET'])]
    public function getBalance(): JsonResponse
    {
        $loggedUser = $this->security->getUser();
        if (!$loggedUser instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        return new JsonResponse([
            'balance' => $this->accountService->getBalance($loggedUser->getId()),
        ]);
    }
}
