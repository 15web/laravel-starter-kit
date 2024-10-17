<?php

declare(strict_types=1);

namespace Dev\Tests\Unit\Blog\Domain;

use App\Module\Blog\Domain\Post;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[TestDox('Блог')]
final class BlogTest extends TestCase
{
    #[TestDox('Создание записи')]
    public function testEntity(): void
    {
        $title = 'Заголовок';
        $author = 'Автор';
        $content = 'Контент';

        $entity = new Post(
            title: $title,
            author: $author,
            content: $content,
        );

        self::assertSame($title, $entity->getTitle());
        self::assertSame($author, $entity->getAuthor());
        self::assertSame($content, $entity->getContent());
    }
}
