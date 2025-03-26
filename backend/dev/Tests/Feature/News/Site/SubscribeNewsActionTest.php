<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\News\Site;

use Dev\OpenApi\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Подписка на новости')]
final class SubscribeNewsActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this
            ->postJson('api/subscribe', [
                'email' => 'user@example.com',
            ])
            ->assertOk();

        /**
         * @var array{
         *      status: non-empty-string
         *  } $data
         */
        $data = $response->json('data');

        self::assertSame('success', $data['status']);
    }

    /**
     * @param array<int|string> $body
     */
    #[DataProvider('notValidRequestProvider')]
    #[TestDox('Неправильный запрос')]
    public function testBadRequest(array $body): void
    {
        $this
            ->postJson(
                uri: 'api/subscribe',
                data: $body,
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой email' => [['email' => '']];

        yield 'невалидный email' => [['email' => 'fake']];
    }
}
