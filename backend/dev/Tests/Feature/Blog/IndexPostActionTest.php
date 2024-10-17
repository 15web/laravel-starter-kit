<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Blog;

use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка просмотра всех записей в блоге')]
final class IndexPostActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $this
            ->postJson('api/blog', ['title' => 'Title1', 'author' => 'Author1', 'content' => 'Content1'])
            ->assertOk();

        $this
            ->postJson('api/blog', ['title' => 'Title2', 'author' => 'Author2', 'content' => 'Content2'])
            ->assertOk();

        $response = $this->getJson('api/blog')->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     author: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json();

        self::assertCount(2, $data);

        self::assertIsNumeric($data[0]['id']);
        self::assertSame($data[0]['title'], 'Title1');
        self::assertSame($data[0]['author'], 'Author1');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']));

        self::assertIsNumeric($data[1]['id']);
        self::assertSame($data[1]['title'], 'Title2');
        self::assertSame($data[1]['author'], 'Author2');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['createdAt']));
    }

    #[TestDox('Нет записей')]
    public function testEmptyCollection(): void
    {
        $this
            ->getJson('api/blog')
            ->assertOk()
            ->assertJson([]);
    }
}
