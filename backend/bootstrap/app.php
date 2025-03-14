<?php

declare(strict_types=1);

use Admin\Infrastructure\Router\RouteRegistrar;
use App\Infrastructure\Request\ForceJsonMiddleware;
use App\Logger\Http\Middleware\LogRequestMiddleware;
use Dev\OpenApi\Middleware\ValidateOpenApiSchemaMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: static fn (): Router => Route::group([], static fn() => new RouteRegistrar()()),
    )
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
