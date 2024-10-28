<?php

declare(strict_types=1);

namespace App\Products\Http\Category\Index;

use DateTimeImmutable;

/**
 * Результат вывода "дерева" категорий товаров
 */
final readonly class IndexCategoryData
{
    /**
     * @param int $id Id категории
     * @param string $title Заголовок категории
     * @param iterable<self> $children Вложенные категории
     * @param DateTimeImmutable $createdAt Дата создания категории
     * @param DateTimeImmutable|null $updatedAt Дата последнего обновления категории
     */
    public function __construct(
        public int $id,
        public string $title,
        public iterable $children,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}
}
