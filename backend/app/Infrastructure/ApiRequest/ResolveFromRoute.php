<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

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
     * @template T of ApiRequest
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function __invoke(string $className): ApiRequest
    {
        try {
            /** @var T $request */
            $request = $this->serializer->denormalize(
                data: Route::current()?->parameters() ?? [],
                type: $className,
            );

            return $request;
        } catch (Throwable $e) {
            throw ApiException::createBadRequestException('Неверный формат запроса', Error::BAD_REQUEST, $e);
        }
    }
}
