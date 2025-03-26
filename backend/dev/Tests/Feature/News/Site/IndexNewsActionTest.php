<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News\Site;

use DateTimeImmutable;
use DateTimeInterface;
use Dev\OpenApi\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Illuminate\Support\Facades\Config;
use Override;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Получение списка новостей')]
final class IndexNewsActionTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        // Устанавливаем завышенное значение рейт лимитера для тестов
        Config::set('auth.rate_limiter_max_attempts.login', 100);
    }

    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $adminAuth = $this->auth('admin@example.test');

        $this
            ->withToken($adminAuth['token'])
            ->postJson('api/news', ['title' => 'Title1'])
            ->assertOk();

        $this
            ->withToken($adminAuth['token'])
            ->postJson('api/news', ['title' => 'Title2'])
            ->assertOk();

        $this
            ->withToken($adminAuth['token'])
            ->postJson('api/news', ['title' => 'Title3'])
            ->assertOk();

        $auth = $this->auth('admin@example.test');

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
        self::assertSame('Title1', $data[0]['title']);
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data[0]['createdAt']),
        );

        self::assertIsNumeric($data[1]['id']);
        self::assertSame('Title2', $data[1]['title']);
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data[1]['createdAt']),
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
        self::assertSame('Title3', $data[0]['title']);
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data[0]['createdAt']),
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
            ->getJson(
                uri: 'api/news',
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertUnauthorized();
    }

    #[TestDox('Запрос с ролью администратора')]
    public function testForbidden(): void
    {
        $auth = $this->auth('admin@example.test');

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title1'])
            ->assertOk();
    }
}
