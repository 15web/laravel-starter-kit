<?php

declare(strict_types=1);

namespace Dev\Telescope;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер для Telescope
 */
final class TelescopeServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        if (!class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            return;
        }

        /** @var bool $isLocal */
        $isLocal = $this->app->environment('local');
        if (!$isLocal) {
            return;
        }

        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeConfigProvider::class);
    }
}
