<?php

declare(strict_types=1);

namespace App\Module\Filter\Contract;

use App\Module\Filter\SearchRequest;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
interface SearchFilter
{
    public function findResults(SearchRequest $searchRequest): string;
}
