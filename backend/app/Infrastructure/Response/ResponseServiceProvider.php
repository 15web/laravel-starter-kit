<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер обработчика ответа
 */
final class ResponseServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ResolveResponse::class);
        $this->app->bind(ResolveSuccessResponse::class);
    }
}
