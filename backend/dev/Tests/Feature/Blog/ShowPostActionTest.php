<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Blog;

use DateTimeImmutable;
use DateTimeInterface;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка просмотра записи в блоге')]
final class ShowPostActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $this
            ->postJson('api/blog', ['title' => 'Title', 'author' => 'Author', 'content' => 'Content'])
            ->assertOk();

        $response = $this
            ->getJson('api/blog/Title')
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     author: non-empty-string,
         *     content: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null,
         * } $data
         */
        $data = $response->json('data');

        self::assertIsNumeric($data['id']);
        self::assertSame('Title', $data['title']);
        self::assertSame('Author', $data['author']);
        self::assertSame('Content', $data['content']);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с не найдена')]
    public function testNotFound(): void
    {
        $this
            ->getJson('api/blog/Title')
            ->assertNotFound();
    }
}
