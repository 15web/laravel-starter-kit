<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiRequest;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Serializer;

final class ResolveApiRequest
{
    public function __construct(
        private Request $request,
        private Serializer $serializer,
    ) {
    }

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
        } catch (\Throwable $e) {
            throw ApiException::createBadRequestException('Неверный формат запроса', Error::BAD_REQUEST, $e);
        }
    }
}
