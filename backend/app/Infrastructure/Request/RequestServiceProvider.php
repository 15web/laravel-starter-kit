<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер обработчика запросов
 */
final class RequestServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ResolveRequest::class);
    }
}
