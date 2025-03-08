<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

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
#[TestDox('Обновление новости')]
final class UpdateNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $auth = $this->auth();

        $response = $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title'])
            ->assertOk();

        /** @var positive-int $newsId */
        $newsId = $response->json('data.id');

        $response = $this
            ->withToken($auth['token'])
            ->postJson('api/news/Title', [
                'title' => 'New Title',
            ])
            ->assertOk();

        /**
         * @var array{
         *     id: positive-int,
         *     title: non-empty-string,
         *     createdAt: non-empty-string,
         *     updatedAt: non-empty-string
         * } $data
         */
        $data = $response->json('data');

        self::assertSame($data['id'], $newsId);
        self::assertSame('New Title', $data['title']);
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data['createdAt']),
        );
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $data['updatedAt']),
        );
    }

    #[TestDox('Запись не найдена')]
    public function testNotFound(): void
    {
        $auth = $this->auth();

        $body = [
            'title' => 'New Title',
        ];

        $this
            ->withToken($auth['token'])
            ->postJson('api/news/Title', $body)
            ->assertNotFound();
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson(
                uri: 'api/news/Title',
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
        $body = [
            'title' => 'New Title',
        ];

        $this
            ->postJson(
                uri: 'api/news/Title',
                data: $body,
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertUnauthorized();
    }
}
