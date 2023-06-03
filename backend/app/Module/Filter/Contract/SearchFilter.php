<?php

declare(strict_types=1);

namespace App\Module\Filter\Contract;

use App\Module\Filter\SearchRequest;

interface SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string;
}
