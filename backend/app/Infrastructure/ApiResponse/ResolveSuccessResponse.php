<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiResponse;

use Illuminate\Http\JsonResponse;

final class ResolveSuccessResponse
{
    public function __construct(private ResolveApiResponse $resolveApiResponse)
    {
    }

    public function __invoke(): JsonResponse
    {
        return ($this->resolveApiResponse)(['success' => true]);
    }
}
