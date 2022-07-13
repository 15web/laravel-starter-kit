<?php

declare(strict_types=1);

namespace App\Module\Filter\Filter1;

use App\Module\Filter\SearchFilter;
use App\Module\Filter\SearchRequest;

final class Filter1 implements SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string
    {
        return 'Filter1 result: '.$searchRequest->query;
    }
}
