<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\List;

use DateTimeImmutable;

final class NewsListData
{
    public function __construct(
        public int $id,
        public string $title,
        public DateTimeImmutable $createdAt,
    ) {}
}
