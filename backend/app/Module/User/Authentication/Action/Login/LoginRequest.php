<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Infrastructure\ApiRequest\ApiRequest;
use Webmozart\Assert\Assert;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class LoginRequest implements ApiRequest
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
