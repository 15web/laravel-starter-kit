<?php

declare(strict_types=1);

namespace App\Module\Filter\Filter2;

use App\Module\Filter\SearchFilter;
use App\Module\Filter\SearchRequest;

final class Filter2 implements SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string
    {
        return 'Filter2 result: '.$searchRequest->query;
    }
}
