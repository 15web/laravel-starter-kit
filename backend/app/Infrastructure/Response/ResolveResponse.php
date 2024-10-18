<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Сериализует объект ответа в json
 */
final readonly class ResolveResponse
{
    public function __construct(
        private Serializer $serializer,
    ) {}

    public function __invoke(mixed $responseData): JsonResponse
    {
        $normalizedResponseData = $this->serializer->serialize(
            data: $responseData,
            format: JsonEncoder::FORMAT,
        );

        return new JsonResponse(
            data: $normalizedResponseData,
            json: true
        );
    }
}
