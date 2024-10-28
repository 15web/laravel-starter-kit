<?php

declare(strict_types=1);

namespace App\Blog\Http\Store;

use DateTimeImmutable;

/**
 * Результат создания записи в блоге
 */
final readonly class StorePostResponse
{
    /**
     * @param positive-int $id Id записи
     * @param non-empty-string $title Заголовок записи
     * @param DateTimeImmutable $createdAt Дата создания записи
     * @param ?DateTimeImmutable $updatedAt Дата последнего обновления записи
     */
    public function __construct(
        public int $id,
        public string $title,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {}
}
