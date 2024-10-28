<?php

declare(strict_types=1);

namespace App\News\Http\Site\Show;

use DateTimeImmutable;

/**
 * Ответ для просмотра новости
 */
final readonly class ShowNewsResponse
{
    /**
     * @param positive-int $id Id новости
     * @param non-empty-string $title Заголовок новости
     * @param DateTimeImmutable $createdAt Дата создания новости
     * @param DateTimeImmutable|null $updatedAt Дата обновления новости
     */
    public function __construct(
        public int $id,
        public string $title,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {}
}
