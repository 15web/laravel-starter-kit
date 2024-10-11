<?php

declare(strict_types=1);

namespace App\Module\Products\Http\Category\Store;

use DateTimeImmutable;

/**
 * Результат создания категории товаров
 */
final readonly class StoreCategoryResponse
{
    /**
     * @param positive-int $id Id категории
     * @param non-empty-string $title Заголовок категории
     * @param DateTimeImmutable $createdAt Дата создания категории
     * @param DateTimeImmutable|null $updatedAt Дата последнего обновления категории
     */
    public function __construct(
        public int $id,
        public string $title,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {}
}
