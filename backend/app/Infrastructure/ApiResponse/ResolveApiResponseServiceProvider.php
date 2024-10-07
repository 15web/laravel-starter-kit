<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiResponse;

use Illuminate\Support\ServiceProvider;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class ResolveApiResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResolveApiResponse::class);
        $this->app->bind(ResolveSuccessResponse::class);
    }
}
