<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException;

use App\Infrastructure\ApiException\Handler\ExceptionHandler;
use CuyZ\Valinor\Mapper\Tree\Message\ErrorMessage;
use CuyZ\Valinor\Mapper\Tree\Message\MessageBuilder;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Support\ServiceProvider;
use Override;
use Throwable;
use Webmozart\Assert\InvalidArgumentException;

/**
 * Сервис провайдер для обработки исключений
 */
final class ApiExceptionServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(
            abstract: ExceptionHandlerContract::class,
            concrete: ExceptionHandler::class,
        );

        $this->bindValidationMapper();
    }

    private function bindValidationMapper(): void
    {
        $mapperBuilder = new MapperBuilder()
            ->filterExceptions(static function (Throwable $exception): ErrorMessage {
                if ($exception instanceof InvalidArgumentException) {
                    return MessageBuilder::from($exception);
                }

                throw $exception;
            });

        $this->app->bind(
            abstract: TreeMapper::class,
            concrete: static fn (): TreeMapper => $mapperBuilder->mapper(),
        );
    }
}
