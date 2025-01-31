<?php

declare(strict_types=1);

namespace App\Components\Account\Communication;

use App\Components\Account\Business\AccountService;
use App\Components\Account\Communication\Form\AccountForm;
use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccountController extends AbstractController
{
    public function __construct(
        private AccountEntityManager $entityManager,
        private AccountMapper $mapper,
        private Security $security,
        private AccountService $accountService,
    ) {
    }

    #[Route('/account', name: 'account_page')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(AccountForm::class);

        $form->handleRequest($request);

        $loggedUser = $this->security->getUser();
        if (!$loggedUser instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this resource');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $dateStamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

            $transferDTO = $this->mapper->createTransferDTO(
                [
                    'id' => 1,
                    'amount' => $data['amount'],
                    'date' => $dateStamp,
                ]
            );

            $this->entityManager->add($loggedUser, $transferDTO);

            return $this->redirectToRoute('account_page');
        }

        $balance = $this->accountService->getBalance($loggedUser->getId());

        return $this->render('account/account.html.twig', [
            'form' => $form,
            'balance' => $balance,
        ]);
    }
}
