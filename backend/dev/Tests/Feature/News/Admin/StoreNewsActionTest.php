<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News\Admin;

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
#[TestDox('Создание новости')]
final class StoreNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $auth = $this->auth('admin@example.test');

        $response = $this
            ->withToken($auth['token'])
            ->postJson('api/news', [
                'title' => 'Title',
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string|null
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
        $auth = $this->auth('admin@example.test');

        $body = [
            'title' => 'Title',
        ];

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', $body)
            ->assertOk();

        $response = $this
            ->postJson('api/news', $body)
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
        $auth = $this->auth('admin@example.test');

        $this
            ->withToken($auth['token'])
            ->postJson(
                uri: 'api/news',
                data: $body,
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой заголовок' => [['title' => '']];
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->postJson(
                uri: 'api/news',
                data: ['title' => 'Title'],
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertUnauthorized();
    }

    #[TestDox('Запрос с ролью пользователя')]
    public function testForbidden(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson(
                uri: 'api/news',
                data: ['title' => 'Title'],
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertForbidden();
    }
}
