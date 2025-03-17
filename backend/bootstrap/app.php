<?php

declare(strict_types=1);

use App\Infrastructure\OpenApiSchemaValidator\Middleware\ValidateOpenApiSchemaMiddleware;
use App\Infrastructure\Request\ForceJsonMiddleware;
use App\Logger\Http\Middleware\LogRequestMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Infrastructure/Console/Commands',
    ])
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware
            ->append(ForceJsonMiddleware::class)
            ->append(ValidateOpenApiSchemaMiddleware::class)
            ->append(LogRequestMiddleware::class);
    })
    ->withExceptions()
    ->create();
