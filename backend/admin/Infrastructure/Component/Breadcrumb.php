<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component;

/**
 * "Хлебные крошки"
 */
final class Breadcrumb
{
    /**
     * @param non-empty-string $title
     * @param non-empty-string|null $url
     */
    public function __construct(
        public string $title,
        public ?string $url = null,
    ) {}
}
