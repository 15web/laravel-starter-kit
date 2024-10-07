<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер обработчика запросов
 */
final class ResolveApiRequestServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ResolveApiRequest::class);
    }
}
