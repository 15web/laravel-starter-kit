<?php

declare(strict_types=1);

namespace App\Module\Filter\Action;

use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\Filter\FilterAggregator;
use App\Module\Filter\SearchRequest;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка для поиска записей с учетом фильтров
 */
final readonly class FilterAction
{
    public function __construct(
        private FilterAggregator $aggregator,
        private ResolveRequest $resolveApiRequest,
        private ResolveResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/filter')]
    public function __invoke(): JsonResponse
    {
        $searchRequest = ($this->resolveApiRequest)(SearchRequest::class);

        $searchAggregate = $this->aggregator->run($searchRequest);

        return ($this->resolveApiResponse)($searchAggregate);
    }
}
