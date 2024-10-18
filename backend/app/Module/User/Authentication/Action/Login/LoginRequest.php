<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос на вход
 */
final readonly class LoginRequest implements Request
{
    public function __construct(
        private string $email,
        private string $password,
    ) {
        Assert::email($email);
        Assert::notEmpty($password);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
