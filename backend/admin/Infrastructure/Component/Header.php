<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component;

/**
 * Шапка страницы адинки
 */
final class Header
{
    /**
     * @param non-empty-string $title
     * @param non-empty-string|null $subtitle
     * @param list<Breadcrumb> $breadcrumbs
     */
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public array $breadcrumbs = [],
    ) {}
}
