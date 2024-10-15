<?php

declare(strict_types=1);

namespace Tests\Feature\Blog;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

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
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertSame($data['author'], 'Author');
        self::assertSame($data['content'], 'Content');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с не найдена')]
    public function testExists(): void
    {
        $this
            ->getJson('api/blog/Title')
            ->assertNotFound();
    }
}
