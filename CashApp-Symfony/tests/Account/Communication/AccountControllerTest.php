<?php

declare(strict_types=1);

namespace App\Tests\Account\Communication;

use App\Components\User\Persistence\UserRepository;
use App\Tests\Config;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    public function testIndexRendersPageWithAuthenticatedUser(): void
    {
        $user = $this->userRepository->findOneBy(['email' => Config::USER_EMAIL_ONE]);
        $this->client->loginUser($user);

        $this->client->request('GET', '/account');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="account_form"]');
        self::assertSelectorExists('input[name="account_form[amount]"]');
        self::assertSelectorExists('button[type="submit"]');
    }

    public function testIndexRedirectsWithNotAuthenticatedUser(): void
    {
        $this->client->request('GET', '/account');

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
