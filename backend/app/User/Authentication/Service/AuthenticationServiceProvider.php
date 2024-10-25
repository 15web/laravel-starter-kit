<?php

declare(strict_types=1);

namespace App\User\Authentication\Service;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер аутентификации
 */
final class AuthenticationServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
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
