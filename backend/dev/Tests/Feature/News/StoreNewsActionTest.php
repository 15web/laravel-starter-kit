<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use App\Contract\Error;
use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка создания записи в новостях')]
final class StoreNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $auth = $this->auth();

        $response = $this
            ->withToken($auth['token'])
            ->postJson('api/news/create', [
                'title' => 'Title',
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         * } $data
         */
        $data = $response->json();

        self::assertIsNumeric($data['id']);
        self::assertSame($data['title'], 'Title');
        self::assertInstanceOf(DateTimeImmutable::class, DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']));
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
            ->postJson('api/news/create', $body)
            ->assertOk();

        $response = $this
            ->postJson('api/news/create', $body)
            ->assertOk();

        $this->assertApiError($response, Error::NEWS_EXISTS->value);
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
            ->postJson('api/news/create', $body)
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
            ->postJson('api/news/create', $body)
            ->assertUnauthorized();
    }
}
