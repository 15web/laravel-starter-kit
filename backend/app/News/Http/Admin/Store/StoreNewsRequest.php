<?php

declare(strict_types=1);

namespace App\News\Http\Admin\Store;

use App\Infrastructure\Request\Request;

/**
 * Запрос создания новости
 */
final readonly class StoreNewsRequest implements Request
{
    /**
     * @param non-empty-string $title Заголовок новости
     */
    public function __construct(
        public string $title,
    ) {}
}
