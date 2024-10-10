<?php

declare(strict_types=1);

namespace Dev\Tests\Unit\News\Domain;

use App\Module\News\Domain\News;
use DateTimeImmutable;
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

        self::assertSame((new DateTimeImmutable())->format(DateTimeImmutable::ATOM), $entity->getCreatedAt()->format(DateTimeImmutable::ATOM));
        self::assertSame($title, $entity->getTitle());
        self::assertNull($entity->getUpdatedAt());
    }

    #[TestDox('Обновление новости')]
    public function testUpdateEntity(): void
    {
        $title = 'Заголовок';
        $newTitle = 'Новый Заголовок';

        $entity = new News(
            title: $title,
        );

        self::assertSame((new DateTimeImmutable())->format(DateTimeImmutable::ATOM), $entity->getCreatedAt()->format(DateTimeImmutable::ATOM));
        self::assertSame($title, $entity->getTitle());
        self::assertNull($entity->getUpdatedAt());

        $entity->setTitle($newTitle);

        self::assertSame($newTitle, $entity->getTitle());

        /** @var DateTimeImmutable $updatedAt */
        $updatedAt = $entity->getUpdatedAt();
        self::assertSame((new DateTimeImmutable())->format(DateTimeImmutable::ATOM), $updatedAt->format(DateTimeImmutable::ATOM));
    }
}
