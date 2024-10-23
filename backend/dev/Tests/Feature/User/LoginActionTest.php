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
                'email' => 'user@example.com',
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
        $data = $response->json('data');

        self::assertSame($data['email'], 'user@example.com');
        self::assertCount(1, $data['roles']);
        self::assertSame($data['roles'][0], 'user');
        self::assertNotEmpty($data['token']);
    }

    #[TestDox('Неправильный пароль')]
    public function testIncorrectPassword(): void
    {
        $this->auth();

        $this
            ->postJson('api/auth/login', [
                'email' => 'user@example.com',
                'password' => 'fakePassword',
            ])
            ->assertUnauthorized();
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

        yield 'пустой пароль' => [['email' => 'user@example.com', 'password' => '']];

        yield 'короткий пароль' => [['email' => 'user@example.com', 'password' => '123']];
    }
}
