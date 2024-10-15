<?php

declare(strict_types=1);

namespace Tests\Feature\Blog;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

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

        self::assertIsNumeric($data[0]['id']);
        self::assertSame($data[0]['title'], 'Title1');
        self::assertSame($data[0]['author'], 'Author1');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data[0]['createdAt']));

        self::assertIsNumeric($data[1]['id']);
        self::assertSame($data[1]['title'], 'Title2');
        self::assertSame($data[1]['author'], 'Author2');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data[1]['createdAt']));
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
