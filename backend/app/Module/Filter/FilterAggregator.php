<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Module\Filter\Contract\SearchFilter;

final class FilterAggregator
{
    /**
     * @var SearchFilter[]
     */
    private array $filters;

    public function __construct(SearchFilter ...$filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return string[]
     */
    public function run(SearchRequest $request): array
    {
        $data = [];

        foreach ($this->filters as $filter) {
            $data[] = $filter->findResults($request);
        }

        return $data;
    }
}
