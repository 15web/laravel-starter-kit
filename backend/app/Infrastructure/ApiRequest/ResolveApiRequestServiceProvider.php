<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use Illuminate\Support\ServiceProvider;

final class ResolveApiRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResolveApiRequest::class);
    }
}
