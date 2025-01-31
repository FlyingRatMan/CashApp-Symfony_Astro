<?php

declare(strict_types=1);

namespace App\Tests;

interface Config
{
    public const USER_NAME_ONE = 'User1';
    public const USER_NAME_TWO = 'User2';
    public const USER_NAME_THREE = 'User3';
    public const USER_EMAIL_ONE = 'user1@example.com';
    public const USER_EMAIL_TWO = 'user2@example.com';
    public const USER_EMAIL_THREE = 'user3@example.com';
    public const USER_PASSWORD = '!StrongPass1';
    public const USER_PASSWORD_INVALID = '123';
    public const USER_EMAIL_DOES_NOT_EXIST = 'doesNotExist@example.com';
}
