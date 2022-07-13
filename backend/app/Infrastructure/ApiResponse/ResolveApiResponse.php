<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class ResolveApiResponse
{
    public function __construct(
        private Serializer $serializer,
    ) {
    }

    public function __invoke(mixed $responseData): JsonResponse
    {
        $normalizedResponseData = $this->serializer->serialize($responseData, JsonEncoder::FORMAT);

        return new JsonResponse($normalizedResponseData, json: true);
    }
}
