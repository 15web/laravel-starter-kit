<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Illuminate\Http\JsonResponse;

/**
 * Возвращает json-ответ "успех"
 */
final readonly class ResolveSuccessResponse
{
    public function __construct(private ResolveResponse $resolveApiResponse) {}

    public function __invoke(): JsonResponse
    {
        return ($this->resolveApiResponse)(
            new ApiObjectResponse(
                data: new SuccessResponse(),
            )
        );
    }
}
