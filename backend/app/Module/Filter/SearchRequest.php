<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Infrastructure\Request\Request;

final class SearchRequest implements Request
{
    public function __construct(public ?string $query) {}
}
