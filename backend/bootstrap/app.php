<?php

declare(strict_types=1);

use App\Infrastructure\ApiException\Handler\Handler;
use App\Infrastructure\OpenApiSchemaValidator\Middleware\ValidateOpenApiSchemaMiddleware;
use App\Module\User\Authorization\Http\CheckRoleGrantedMiddleware;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Infrastructure/Console/Commands',
    ])
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware->append([
            CheckRoleGrantedMiddleware::class,
            ValidateOpenApiSchemaMiddleware::class,
        ]);
    })
    ->withExceptions()
    ->withSingletons([
        ExceptionHandler::class => Handler::class,
    ])
    ->create();
