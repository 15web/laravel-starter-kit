<?php

declare(strict_types=1);

namespace App\Module\Filter\Filters\Filter1;

use App\Module\Filter\Contract\SearchFilter;
use App\Module\Filter\SearchRequest;
use Override;

/**
 * Пример фильтра 1
 */
final class Filter implements SearchFilter
{
    #[Override]
    public function findResults(SearchRequest $searchRequest): string
    {
        return "Filter1 result: {$searchRequest->query}";
    }
}
