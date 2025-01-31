<?php

declare(strict_types=1);

namespace App\Tests\User\Communication;

use App\Tests\Config;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRenderForm(): void
    {
        $this->client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Reset your password');
        self::assertSelectorExists('form.form');
        self::assertSelectorExists('input[name="reset_password_link_request_form[email]"]');
        self::assertSelectorExists('button.form_primary-btn');
    }

    public function testRequestRedirectsToCheckEmailOnValidEmail(): void
    {
        $this->client->request('GET', '/reset-password');
        $this->client->submitForm('Send password reset email', [
            'reset_password_link_request_form[email]' => Config::USER_EMAIL_ONE,
        ]);

        self::assertResponseRedirects('/reset-password/check-email');
        $this->client->followRedirect();
        self::assertPageTitleSame('Password Reset Email Sent');
        self::assertSelectorExists('div.page');
    }

    public function testRequestRedirectsToCheckEmailOnUnrecognisedEmail(): void
    {
        $this->client->request('GET', '/reset-password');
        $this->client->submitForm('Send password reset email', [
            'reset_password_link_request_form[email]' => 'unrecognised@email.com',
        ]);

        self::assertResponseRedirects('/reset-password/check-email');
        $this->client->followRedirect();
        self::assertPageTitleSame('Password Reset Email Sent');
        self::assertSelectorExists('div.page');
    }

    public function testCheckEmailRendersPage(): void
    {
        $this->client->request('GET', '/reset-password/check-email');

        self::assertSelectorExists('div.page');
        self::assertPageTitleSame('Password Reset Email Sent');
    }

    public function testResetWithNoTokenInSession(): void
    {
        $this->client->request('GET', '/reset-password/reset');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertSelectorNotExists('form');
    }
}
