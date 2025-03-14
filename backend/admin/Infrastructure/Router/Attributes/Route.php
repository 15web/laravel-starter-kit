<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Router\Attributes;

use Attribute;

/**
 * С этим атрибутом компонент доступен роутеру
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Route
{
    public function __construct(
        public string $uri,
        public array $middleware = [],
        public ?string $name = null,
    ) {}
}
