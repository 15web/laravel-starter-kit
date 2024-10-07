<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category\Create;

use App\Infrastructure\ApiRequest\ApiRequest;

/**
 * Запрос на создание категории товаров
 */
final readonly class CategoryCreateRequest implements ApiRequest
{
    public function __construct(
        private string $title,
        private ?int $parent = null,
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }
}
