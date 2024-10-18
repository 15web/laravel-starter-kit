<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Index;

use DateTimeImmutable;

/**
 * Объект для просмотра в списке новостей
 */
final class IndexNewsData
{
    /**
     * @param positive-int $id Id новости
     * @param non-empty-string $title Заголовок новости
     * @param DateTimeImmutable $createdAt Дата создания новости
     */
    public function __construct(
        public int $id,
        public string $title,
        public DateTimeImmutable $createdAt,
    ) {}
}
