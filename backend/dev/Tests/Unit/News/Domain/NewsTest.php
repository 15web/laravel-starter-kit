<?php

declare(strict_types=1);

namespace Dev\Tests\Unit\News\Domain;

use App\Module\News\Model\News;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[TestDox('Новости')]
final class NewsTest extends TestCase
{
    #[TestDox('Создание новости')]
    public function testEntity(): void
    {
        $title = 'Заголовок';

        $entity = new News(
            title: $title,
        );

        self::assertSame($title, $entity->getTitle());
    }
}
