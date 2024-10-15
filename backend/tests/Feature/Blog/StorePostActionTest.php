<?php

declare(strict_types=1);

namespace Tests\Feature\Blog;

use App\Contract\Error;
use App\Infrastructure\Middleware\ValidateOpenApiSchemaMiddleware;
use Carbon\Carbon;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

/**
 * @internal
 */
#[TestDox('Ручка создания записи в блоге')]
final class StorePostActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $response = $this
            ->postJson('api/blog', [
                'title' => 'Title',
                'author' => 'Author',
                'content' => 'Content',
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null,
         * } $data
         */
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertInstanceOf(Carbon::class, Carbon::createFromFormat('c', $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с таким заголовком уже существует')]
    public function testExists(): void
    {
        $body = [
            'title' => 'Title',
            'author' => 'Author',
            'content' => 'Content',
        ];

        $this
            ->postJson('api/blog', $body)
            ->assertOk();

        $response = $this
            ->postJson('api/blog', $body)
            ->assertOk();

        $this->assertApiError($response, Error::EXISTS->value);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $body[ValidateOpenApiSchemaMiddleware::VALIDATE_REQUEST_KEY] = false;

        $this
            ->postJson('api/blog', $body)
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой заголовок' => [['title' => '', 'author' => 'Author', 'content' => 'Content']];

        yield 'пустой автор' => [['title' => 'Title', 'author' => '', 'content' => 'Content']];

        yield 'пустой контент' => [['title' => 'Title', 'author' => 'Author', 'content' => '']];
    }
}
