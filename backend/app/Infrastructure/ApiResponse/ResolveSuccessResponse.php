<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiResponse;

use Illuminate\Http\JsonResponse;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class ResolveSuccessResponse
{
    public function __construct(private ResolveApiResponse $resolveApiResponse) {}

    public function __invoke(): JsonResponse
    {
        return ($this->resolveApiResponse)(['success' => true]);
    }
}
