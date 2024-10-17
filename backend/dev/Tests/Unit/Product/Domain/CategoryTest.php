<?php

declare(strict_types=1);

namespace Dev\Tests\Unit\Product\Domain;

use App\Module\Products\Domain\Category;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[TestDox('Категория товаров')]
final class CategoryTest extends TestCase
{
    #[TestDox('Создание категории')]
    public function testEntity(): void
    {
        $title = 'Заголовок';

        $entity = new Category(
            title: $title,
        );

        self::assertSame((new DateTimeImmutable())->format(DateTimeImmutable::ATOM), $entity->getCreatedAt()->format(DateTimeImmutable::ATOM));
        self::assertSame($title, $entity->getTitle());
    }
}
