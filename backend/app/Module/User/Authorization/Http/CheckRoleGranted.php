<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\Http;

use App\Module\User\Authorization\Domain\Role;
use App\Module\User\User\Domain\User;

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
