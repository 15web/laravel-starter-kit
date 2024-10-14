<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Show;

use DateTimeImmutable;

/**
 * Ответ для просмотра записи в блоге
 */
final readonly class ShowPostResponse
{
    /**
     * @param positive-int $id Id записи
     * @param non-empty-string $title Заголовок записи
     * @param non-empty-string $author Автор записи
     * @param non-empty-string $content Текст записи
     * @param DateTimeImmutable $createdAt Дата создания записи
     * @param DateTimeImmutable|null $updatedAt Дата последнего обновления записи
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $author,
        public string $content,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {}
}
