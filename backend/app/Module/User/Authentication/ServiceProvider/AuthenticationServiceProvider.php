<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\ServiceProvider;

use App\Module\User\Authentication\UserProvider\TokenUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер аутентификации
 */
final class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lang::addNamespace('user', app_path('Module/User/lang'));
    }

    #[Override]
    public function register(): void
    {
        $this->app->bind(TokenUserProvider::class);

        /** @var TokenUserProvider $userProvider */
        $userProvider = $this->app->make(TokenUserProvider::class);

        /** @var AuthManager $authManager */
        $authManager = $this->app->make(AuthManager::class);
        $authManager->provider(
            'doctrine',
            static fn (): TokenUserProvider => $userProvider,
        );
    }
}
