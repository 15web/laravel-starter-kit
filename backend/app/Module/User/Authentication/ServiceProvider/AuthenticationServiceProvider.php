<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\ServiceProvider;

use App\Module\User\Authentication\UserProvider\TokenUserProvider;
use Illuminate\Support\ServiceProvider;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TokenUserProvider::class);

        /**
         * @var \Illuminate\Auth\AuthManager $authManager
         */
        $authManager = $this->app->make(\Illuminate\Auth\AuthManager::class);
        $authManager->provider(
            'doctrine',
            static fn ($app) => $app->make(TokenUserProvider::class)
        );
    }
}
