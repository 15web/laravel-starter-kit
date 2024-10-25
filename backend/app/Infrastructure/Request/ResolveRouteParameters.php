<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Infrastructure\ApiException\ApiException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует параметры роутера в объект
 */
final readonly class ResolveRouteParameters
{
    public function __construct(
        private Serializer $serializer,
    ) {}

    /**
     * @template T of Request
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function __invoke(string $className): Request
    {
        try {
            /** @var T $request */
            $request = $this->serializer->denormalize(
                data: Route::current()?->parameters() ?? [],
                type: $className,
            );

            return $request;
        } catch (Throwable $e) {
            throw ApiException::createBadRequestException(
                messages: [$e->getMessage()],
                previous: $e,
            );
        }
    }
}
