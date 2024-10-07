<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use Illuminate\Support\ServiceProvider;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class ResolveApiRequestServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(ResolveApiRequest::class);
    }
}
