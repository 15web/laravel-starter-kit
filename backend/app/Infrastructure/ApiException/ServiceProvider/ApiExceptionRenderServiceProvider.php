<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\ServiceProvider;

use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use Illuminate\Support\ServiceProvider;

final class ApiExceptionRenderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ApiExceptionRender::class);
    }
}
