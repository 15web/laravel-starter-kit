<?php

declare(strict_types=1);

namespace App\Module\Filter;

interface SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string;
}
