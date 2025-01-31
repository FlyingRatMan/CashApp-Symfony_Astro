<?php

namespace App\Tests\User\Communication;

use App\Tests\Config;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/login');
    }

    public function testLoginSuccessful(): void
    {
        $this->client->submitForm('Log in', [
            '_username' => Config::USER_EMAIL_ONE,
            '_password' => Config::USER_PASSWORD,
        ]);

        self::assertResponseRedirects('/account');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }

    public function testLoginWithWrongEmail(): void
    {
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Log in', [
            '_username' => Config::USER_EMAIL_DOES_NOT_EXIST,
            '_password' => Config::USER_PASSWORD_INVALID,
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    public function testLoginWithWrongPassword(): void
    {
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Log in', [
            '_username' => Config::USER_NAME_ONE,
            '_password' => Config::USER_PASSWORD_INVALID,
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }
}
