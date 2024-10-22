<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use Illuminate\Support\Facades\Request as CurrentRequest;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует запрос (body) в объект
 */
final readonly class ResolveRequestBody
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
        /** @var array<array-key, mixed> $data */
        $data = CurrentRequest::post(default: []);

        try {
            /** @var T $apiRequest */
            $apiRequest = $this->serializer->denormalize(
                data: $data,
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
