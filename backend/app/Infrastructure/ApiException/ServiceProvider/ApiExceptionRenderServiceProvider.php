<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\ServiceProvider;

use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class ApiExceptionRenderServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ApiExceptionRender::class);
    }
}
