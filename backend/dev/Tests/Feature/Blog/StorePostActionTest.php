<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Blog;

use App\Infrastructure\ApiException\Handler\ErrorCode;
use DateTimeImmutable;
use DateTimeInterface;
use Dev\OpenApi\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

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
        $data = $response->json('data');

        self::assertIsNumeric($data['id']);
        self::assertSame('Title', $data['title']);
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data['createdAt']));
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

        $this->assertApiError($response, ErrorCode::EXISTS->value);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $this
            ->postJson(
                uri: 'api/blog',
                data: $body,
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
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
