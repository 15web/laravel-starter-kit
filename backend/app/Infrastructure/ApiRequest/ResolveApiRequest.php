<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует запрос в объект
 */
final readonly class ResolveApiRequest
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
            /** @var T $apiRequest */
            $apiRequest = $this->serializer->denormalize(
                data: Request::all(),
                type: $className
            );

            return $apiRequest;
        } catch (Throwable $e) {
            /** @var string $message */
            $message = __('handler.invalid-request-format');

            throw ApiException::createBadRequestException($message, Error::BAD_REQUEST, $e);
        }
    }
}
