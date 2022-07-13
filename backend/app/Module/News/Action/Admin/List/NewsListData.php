<?php

declare(strict_types=1);

namespace App\Module\News\Action\Admin\List;

final class NewsListData
{
    public function __construct(
        public int $id,
        public string $title,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}
