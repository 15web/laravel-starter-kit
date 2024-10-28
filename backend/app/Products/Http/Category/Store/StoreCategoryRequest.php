<?php

declare(strict_types=1);

namespace App\Products\Http\Category\Store;

use App\Infrastructure\Request\Request;

/**
 * Запрос на создание категории товаров
 */
final readonly class StoreCategoryRequest implements Request
{
    /**
     * @param non-empty-string $title
     * @param positive-int|null $parent
     */
    public function __construct(
        public string $title,
        public ?int $parent = null,
    ) {}
}
