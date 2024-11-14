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

        $this
            ->postJson('api/blog', ['title' => 'Title3', 'author' => 'Author3', 'content' => 'Content3'])
            ->assertOk();

        /**
         * Первая страница
         */
        $response = $this
            ->getJson('api/blog?offset=0&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     author: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json('data');

        /** @var non-negative-int $total */
        $total = $response->json('pagination.total');

        self::assertCount(2, $data);

        self::assertIsNumeric($data[0]['id']);
        self::assertSame('Title1', $data[0]['title']);
        self::assertSame('Author1', $data[0]['author']);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']));

        self::assertIsNumeric($data[1]['id']);
        self::assertSame('Title2', $data[1]['title']);
        self::assertSame('Author2', $data[1]['author']);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['createdAt']));

        self::assertSame(3, $total);

        /**
         * Вторая страница
         */
        $response = $this
            ->getJson('api/blog?offset=2&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     author: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json('data');

        /** @var non-negative-int $total */
        $total = $response->json('pagination.total');

        self::assertCount(1, $data);

        self::assertIsNumeric($data[0]['id']);
        self::assertSame('Title3', $data[0]['title']);
        self::assertSame('Author3', $data[0]['author']);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']));

        self::assertSame(3, $total);

        /**
         * Третья страница
         */
        $response = $this
            ->getJson('api/blog?offset=4&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     author: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json('data');

        /** @var non-negative-int $total */
        $total = $response->json('pagination.total');

        self::assertSame([], $data);

        self::assertSame(3, $total);
    }

    #[TestDox('Нет записей')]
    public function testEmptyCollection(): void
    {
        $this
            ->getJson('api/blog')
            ->assertOk()
            ->assertJson(['data' => []]);
    }
}
