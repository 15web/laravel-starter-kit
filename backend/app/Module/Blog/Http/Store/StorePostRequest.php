<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Store;

use App\Infrastructure\Request\Request;

/**
 * Запрос для создания записи в блоге
 */
final readonly class StorePostRequest implements Request
{
    /**
     * @param non-empty-string $title Заголовок записи
     * @param non-empty-string $author Автор записи
     * @param non-empty-string $content Текст записи
     */
    public function __construct(
        public string $title,
        public string $author,
        public string $content,
    ) {}
}
