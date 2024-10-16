<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\User;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка логина')]
final class LoginActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $response = $this
            ->postJson('api/auth/login', [
                'email' => 'test@example.com',
                'password' => '123456',
            ])
            ->assertOk();

        /**
         * @var array{
         *      token: non-empty-string,
         *      roles: list<array{name: string, value: non-empty-string}>,
         *      email: non-empty-string
         *  } $data
         */
        $data = $response->json();

        self::assertSame($data['email'], 'test@example.com');
        self::assertCount(1, $data['roles']);
        self::assertSame($data['roles'][0], ['name' => 'User', 'value' => 'user']);
        self::assertNotEmpty($data['token']);
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
            ->postJson('api/auth/login', $body)
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой email' => [['email' => '', 'password' => '123456']];

        yield 'невалидный email' => [['email' => 'fake', 'password' => '123456']];

        yield 'пустой пароль' => [['email' => 'test@example.com', 'password' => '']];
    }
}
