<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiResponse;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер обработчика ответа
 */
final class ResolveApiResponseServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ResolveApiResponse::class);
        $this->app->bind(ResolveSuccessResponse::class);
    }
}
