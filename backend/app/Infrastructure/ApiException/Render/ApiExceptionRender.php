<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Render;

use App\Infrastructure\ApiException\ApiException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class ApiExceptionRender
{
    public function __construct(private Serializer $serializer) {}

    public function __invoke(ApiException $apiException): JsonResponse
    {
        $apiExceptionResponseJSON = $this->serializer->serialize(
            new ApiExceptionResponse($apiException),
            JsonEncoder::FORMAT
        );

        return new JsonResponse(
            data: $apiExceptionResponseJSON,
            status: $apiException->getStatusCode()->value,
            json: true
        );
    }
}
