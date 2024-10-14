<?php

declare(strict_types=1);

use App\Infrastructure\ApiException\Handler\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Infrastructure/Console/Commands',
    ])
    ->withMiddleware()
    ->withExceptions()
    ->withSingletons([
        ExceptionHandler::class => Handler::class,
    ])
    ->create();
