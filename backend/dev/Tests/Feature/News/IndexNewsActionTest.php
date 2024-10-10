<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Получение списка новостей')]
final class IndexNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title1'])
            ->assertOk();

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title2'])
            ->assertOk();

        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json();

        self::assertCount(2, $data);

        self::assertIsNumeric($data[0]['id']);
        self::assertSame($data[0]['title'], 'Title1');
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']),
        );

        self::assertIsNumeric($data[1]['id']);
        self::assertSame($data[1]['title'], 'Title2');
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[1]['createdAt']),
        );
    }

    #[TestDox('Нет записей')]
    public function testEmptyCollection(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->getJson('api/news')
            ->assertOk()
            ->assertJson([]);
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->getJson(\sprintf('api/news?%s=false', ValidateOpenApiSchema::VALIDATE_REQUEST_KEY))
            ->assertUnauthorized();
    }
}
