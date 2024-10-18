<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use DateTimeImmutable;
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
        $auth = $this->auth();

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
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']));
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись с таким заголовком уже существует')]
    public function testExists(): void
    {
        $auth = $this->auth();

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

        $this->assertApiError($response, Error::EXISTS->value);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $auth = $this->auth();

        $body[ValidateOpenApiSchema::VALIDATE_REQUEST_KEY] = false;

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', $body)
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
        $body = [
            'title' => 'Title',
            ValidateOpenApiSchema::VALIDATE_REQUEST_KEY => false,
        ];

        $this
            ->postJson('api/news', $body)
            ->assertUnauthorized();
    }
}
