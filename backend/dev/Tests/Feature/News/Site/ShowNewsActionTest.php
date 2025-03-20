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
#[TestDox('Просмотр новости')]
final class ShowNewsActionTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        // Устанавливаем завышенное значение рейт лимитера для тестов
        Config::set('auth.rate_limiter_max_attempts.login', 100);
    }

    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $adminAuth = $this->auth('admin@example.test');

        $this
            ->withToken($adminAuth['token'])
            ->postJson('api/news', ['title' => 'Title'])
            ->assertOk();

        $auth = $this->auth();

        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news/Title')
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
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data['createdAt']),
        );
        self::assertNull($data['updatedAt']);
    }

    #[TestDox('Запись не найдена')]
    public function testNotFound(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->getJson('api/news/Title')
            ->assertNotFound();
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->getJson(
                uri: 'api/news/Title',
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
            ->postJson('api/news', ['title' => 'Title'])
            ->assertOk();

        $this
            ->withToken($auth['token'])
            ->getJson('api/news/Title')
            ->assertOk();
    }
}
