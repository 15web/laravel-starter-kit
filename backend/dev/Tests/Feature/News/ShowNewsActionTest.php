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
#[TestDox('Ручка просмотра новости')]
final class ShowNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson('api/news/create', ['title' => 'Title'])
            ->assertOk();

        $response = $this
            ->withToken($auth['token'])
            ->getJson('api/news/info?title=Title')
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
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['createdAt']),
        );
    }

    #[TestDox('Запись не найдена')]
    public function testExists(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->getJson('api/news/info?title=Title')
            ->assertNotFound();
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->getJson(\sprintf('api/news/info?title=Title&%s=false', ValidateOpenApiSchema::VALIDATE_REQUEST_KEY))
            ->assertUnauthorized();
    }
}
