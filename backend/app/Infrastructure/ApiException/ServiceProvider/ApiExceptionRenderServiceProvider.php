<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\ServiceProvider;

use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер для передачи ошибки в ответ
 */
final class ApiExceptionRenderServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ApiExceptionRender::class);
    }
}
