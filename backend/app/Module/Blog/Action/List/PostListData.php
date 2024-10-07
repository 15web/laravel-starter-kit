<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\List;

use DateTimeInterface;

final class PostListData
{
    public function __construct(
        public int $id,
        public string $title,
        public string $author,
        public ?DateTimeInterface $createdAt,
    ) {}
}
