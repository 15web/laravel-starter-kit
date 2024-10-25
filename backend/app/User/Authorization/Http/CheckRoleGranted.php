<?php

declare(strict_types=1);

namespace App\User\Authorization\Http;

use App\User\Authorization\Domain\Role;
use App\User\User\Domain\User;

/**
 * Проверяет доступ к ресурсу по роли пользователя
 */
final readonly class CheckRoleGranted
{
    public function __invoke(User $user, Role $role): bool
    {
        return $user->hasRole($role);
    }
}
