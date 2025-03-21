<?php

declare(strict_types=1);

use App\Infrastructure\Request\ForceJsonMiddleware;
use App\Logger\Http\Middleware\LogRequestMiddleware;
use Dev\OpenApi\Middleware\ValidateOpenApiSchemaMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Infrastructure/Console/Commands',
    ])
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware
            ->append(ForceJsonMiddleware::class)
            ->append(LogRequestMiddleware::class);

        if (class_exists(ValidateOpenApiSchemaMiddleware::class)) {
            $middleware->append(ValidateOpenApiSchemaMiddleware::class);
        }
    })
    ->withExceptions()
    ->create();
