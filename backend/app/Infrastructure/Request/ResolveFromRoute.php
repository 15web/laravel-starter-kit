<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует параметры роутера в объект
 */
final readonly class ResolveFromRoute
{
    public function __construct(
        private Serializer $serializer,
    ) {}

    /**
     * @template T of Request
     *
     * @param class-string<T> $className
     *
     * @return Request
     */
    public function __invoke(string $className): Request
    {
        try {
            /** @var Request $request */
            $request = $this->serializer->denormalize(
                data: Route::current()?->parameters() ?? [],
                type: $className,
            );

            return $request;
        } catch (Throwable $e) {
            throw ApiException::createBadRequestException(
                message: 'Неверный формат запроса',
                type: Error::BAD_REQUEST,
                previous: $e,
            );
        }
    }
}
