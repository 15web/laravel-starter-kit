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

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title3'])
            ->assertOk();

        /**
         * Первая страница
         */
        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news?offset=0&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json('data');

        /** @var non-negative-int $total */
        $total = $response->json('pagination.total');

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

        self::assertSame(3, $total);

        /**
         * Вторая страница
         */
        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news?offset=2&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         * }> $data
         */
        $data = $response->json('data');

        /** @var non-negative-int $total */
        $total = $response->json('pagination.total');

        self::assertCount(1, $data);

        self::assertIsNumeric($data[0]['id']);
        self::assertSame($data[0]['title'], 'Title3');
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data[0]['createdAt']),
        );

        self::assertSame(3, $total);

        /**
         * Третья страница
         */
        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news?offset=4&limit=2')
            ->assertOk();

        /**
         * @var list<array{
         *     id: mixed,
         *     title: non-empty-string,
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
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->getJson('api/news')
            ->assertOk()
            ->assertJson(['data' => []]);
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->getJson(\sprintf('api/news?%s=false', ValidateOpenApiSchema::VALIDATE_REQUEST_KEY))
            ->assertUnauthorized();
    }
}
