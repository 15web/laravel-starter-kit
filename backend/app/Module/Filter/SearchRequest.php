<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Infrastructure\ApiRequest\ApiRequest;

final class SearchRequest implements ApiRequest
{
    public function __construct(public ?string $query)
    {
    }
}
