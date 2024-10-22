<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Удаление новости')]
final class DestroyNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->postJson('api/news', ['title' => 'Title'])
            ->assertOk();

        $response = $this
            ->withToken($auth['token'])
            ->deleteJson('api/news/Title')
            ->assertOk();

        /**
         * @var array{
         *      status: non-empty-string
         *  } $data
         */
        $data = $response->json('data');

        self::assertSame('success', $data['status']);
    }

    #[TestDox('Запись не найдена')]
    public function testNotFound(): void
    {
        $auth = $this->auth();

        $this
            ->withToken($auth['token'])
            ->deleteJson('api/news/Title')
            ->assertNotFound();
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->deleteJson(\sprintf('api/news/Title?%s=false', ValidateOpenApiSchema::VALIDATE_REQUEST_KEY))
            ->assertUnauthorized();
    }
}
