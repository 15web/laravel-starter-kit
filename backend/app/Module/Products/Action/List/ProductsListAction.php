<?php

declare(strict_types=1);

namespace App\Module\Products\Action\List;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
final class ProductsListAction
{
    public function __construct(
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Router\Get('/products/list')]
    public function __invoke(): JsonResponse
    {
        return ($this->resolveApiResponse)([]);
    }
}
