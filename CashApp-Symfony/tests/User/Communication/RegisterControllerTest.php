<?php

declare(strict_types=1);

namespace App\Tests\User\Communication;

use App\Tests\Config;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/register');
    }

    public function testRenderForm(): void
    {
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form');
        self::assertSelectorExists('input[name="register_form[name]"]');
        self::assertSelectorExists('input[name="register_form[email]"]');
        self::assertSelectorExists('input[name="register_form[password]"]');
    }

    public function testRegisterSuccessful(): void
    {
        $this->client->submitForm('register_form[registerBtn]', [
            'register_form[name]' => 'Test User',
            'register_form[email]' => 'register@success.com',
            'register_form[password]' => Config::USER_PASSWORD,
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function testRegisterWithDuplicatedEmail(): void
    {
        $this->client->submitForm('register_form[registerBtn]', [
            'register_form[name]' => Config::USER_NAME_ONE,
            'register_form[email]' => Config::USER_EMAIL_ONE,
            'register_form[password]' => Config::USER_PASSWORD,
        ]);

        self::assertResponseIsUnprocessable();

        self::assertSelectorTextContains('div.form_error > ul > li', 'This email is already registered');
    }

    public function testRegisterWithInvalidPassword(): void
    {
        $this->client->submitForm('register_form[registerBtn]', [
            'register_form[name]' => 'Test User',
            'register_form[email]' => 'invalid@password.com',
            'register_form[password]' => Config::USER_PASSWORD_INVALID,
        ]);

        self::assertResponseIsUnprocessable();

        self::assertSelectorTextContains('div.form_error > ul > li', 'Password is not valid');
    }
}
