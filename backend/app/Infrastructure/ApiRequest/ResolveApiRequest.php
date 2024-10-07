<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Serializer;
use Throwable;

/**
 * Денормализует запрос в объект
 */
final readonly class ResolveApiRequest
{
    public function __construct(
        private Request $request,
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
            return $this->serializer->denormalize($this->request->all(), $className);
        } catch (Throwable $e) {
            /** @var string $message */
            $message = __('handler.invalid-request-format');

            throw ApiException::createBadRequestException($message, Error::BAD_REQUEST, $e);
        }
    }
}
