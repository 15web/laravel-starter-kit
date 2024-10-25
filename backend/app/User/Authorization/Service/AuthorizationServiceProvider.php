<?php

declare(strict_types=1);

namespace App\User\Authorization\Service;

use App\User\Authorization\Domain\Role;
use App\User\Authorization\Http\CheckRoleGranted;
use App\User\User\Domain\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Сервис провайдер авторизации в приложении
 */
final class AuthorizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define(
            ability: CheckRoleGranted::class,
            callback: static fn (User $user, Role $role): bool => (new CheckRoleGranted())(
                user: $user,
                role: $role,
            ),
        );
    }
}
