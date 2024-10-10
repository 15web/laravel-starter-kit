<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Request\Request as ApiRequest;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует запрос в объект
 */
final readonly class ResolveRequest
{
    public function __construct(
        private Request $request,
        private Serializer $serializer,
    ) {}

    /**
     * @template T of Request
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function __invoke(string $className): ApiRequest
    {
        try {
            /** @var T $apiRequest */
            $apiRequest = $this->serializer->denormalize(
                data: $this->request->all(),
                type: $className,
            );

            return $apiRequest;
        } catch (Throwable $e) {
            throw ApiException::createBadRequestException(
                message: 'Неверный формат запроса',
                type: Error::BAD_REQUEST,
                previous: $e,
            );
        }
    }
}
