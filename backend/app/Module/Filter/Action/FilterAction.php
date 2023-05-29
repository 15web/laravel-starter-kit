<?php

declare(strict_types=1);

namespace App\Module\Filter\Action;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Filter\FilterAggregator;
use App\Module\Filter\SearchRequest;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
final class FilterAction
{
    public function __construct(
        private FilterAggregator $aggregator,
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Router\Get('/filter')]
    public function __invoke(): JsonResponse
    {
        $searchRequest = ($this->resolveApiRequest)(SearchRequest::class);

        $searchAggregate = $this->aggregator->run($searchRequest);

        return ($this->resolveApiResponse)($searchAggregate);
    }
}
