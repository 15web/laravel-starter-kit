<?php

declare(strict_types=1);

namespace App\Module\Products\Http\Category\Store;

use App\Infrastructure\ApiRequest\ApiRequest;

/**
 * Запрос на создание категории товаров
 */
final readonly class StoreCategoryRequest implements ApiRequest
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
