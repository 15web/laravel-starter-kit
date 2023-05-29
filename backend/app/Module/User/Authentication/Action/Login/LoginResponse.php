<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Module\User\Authorization\ByRole\Role;
use Webmozart\Assert\Assert;

final class LoginResponse
{
    /**
     * @param array<Role> $roles
     */
    public function __construct(
        private readonly string $token,
        private readonly string $email,
        private readonly array $roles,
    ) {
        Assert::notEmpty($token);
        Assert::email($email);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array<Role>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
