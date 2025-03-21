<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use DateTimeImmutable;
use DateTimeInterface;
use Dev\OpenApi\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Просмотр новости')]
final class ShowNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title'])
            ->assertOk();

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
}
