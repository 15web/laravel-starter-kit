<?php

declare(strict_types=1);

namespace App\Module\Filter\Filters\Filter2;

use App\Module\Filter\Contract\SearchFilter;
use App\Module\Filter\SearchRequest;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class Filter implements SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string
    {
        return 'Filter2 result: '.$searchRequest->query;
    }
}