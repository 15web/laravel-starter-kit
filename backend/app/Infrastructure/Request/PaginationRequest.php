<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

/**
 * Запрос на пагинацию
 */
final readonly class PaginationRequest implements Request
{
    /**
     * @param non-negative-int $offset
     * @param positive-int $limit
     */
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
    ) {}
}
