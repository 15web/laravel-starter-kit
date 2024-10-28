<?php

declare(strict_types=1);

namespace App\Blog\Http\Index;

use DateTimeImmutable;

/**
 * Ответ для списка записей блога
 */
final class IndexPostData
{
    /**
     * @param positive-int $id Id записи
     * @param non-empty-string $title Заголовок записи
     * @param non-empty-string $author Автор записи
     * @param DateTimeImmutable $createdAt Дата создания записи
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $author,
        public DateTimeImmutable $createdAt,
    ) {}
}
