<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\ServiceProvider;

use App\Module\User\Authentication\UserProvider\TokenUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lang::addNamespace('user', app_path('Module/User/lang'));
    }

    public function register(): void
    {
        $this->app->bind(TokenUserProvider::class);

        /** @var AuthManager $authManager */
        $authManager = $this->app->make(AuthManager::class);
        $authManager->provider(
            'doctrine',
            static fn ($app) => $app->make(TokenUserProvider::class)
        );
    }
}
