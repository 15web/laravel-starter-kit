<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка подписки на новости')]
final class SubscribeNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this
            ->postJson('api/news/subscribe', [
                'email' => 'user@example.com',
            ])
            ->assertOk();

        /**
         * @var array{
         *     success: bool
         * } $data
         */
        $data = $response->json();

        self::assertTrue($data['success']);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $body[ValidateOpenApiSchema::VALIDATE_REQUEST_KEY] = false;

        $this
            ->postJson('api/news/subscribe', $body)
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой email' => [['email' => '']];

        yield 'невалидный email' => [['email' => 'fake']];
    }
}
