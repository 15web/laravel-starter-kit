<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Http\Login;

use App\Module\User\Authorization\Domain\Role;

/**
 * Результат входа
 */
final readonly class LoginResponse
{
    /**
     * @param list<Role> $roles
     */
    public function __construct(
        public string $token,
        public string $email,
        public array $roles,
    ) {}
}
