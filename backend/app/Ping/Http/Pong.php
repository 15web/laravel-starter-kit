<?php

declare(strict_types=1);

namespace App\Ping\Http;

use DateTimeImmutable;

final readonly class Pong
{
    /**
     * @param non-empty-string $result
     */
    public function __construct(
        public string $result,
        public DateTimeImmutable $now,
    ) {}
}
