<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\User;

use App\User\User\Domain\User;
use Dev\OpenApi\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Iterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка логина/регистрации')]
final class LoginActionTest extends TestCase
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
        $response = $this
            ->postJson('api/auth/login', [
                'email' => 'user@example.com',
                'password' => '123456',
            ])
            ->assertOk();

        /**
         * @var array{
         *      token: non-empty-string,
         *      roles: list<non-empty-string>,
         *      email: non-empty-string
         *  } $data
         */
        $data = $response->json('data');

        self::assertSame('user@example.com', $data['email']);
        self::assertCount(1, $data['roles']);
        self::assertSame('user', $data['roles'][0]);
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

    #[TestDox('Короткий пароль при регистрации')]
    public function testShortPasswordWhileRegister(): void
    {
        $this
            ->postJson(
                uri: 'api/auth/login',
                data: [
                    'email' => 'user@example.com',
                    'password' => '123',
                ],
            )
            ->assertBadRequest();
    }

    #[TestDox('Короткий пароль при входе')]
    public function testShortPasswordWhileLogin(): void
    {
        $this->auth();

        $this
            ->postJson(
                uri: 'api/auth/login',
                data: [
                    'email' => 'user@example.com',
                    'password' => '123',
                ],
            )
            ->assertUnauthorized();
    }

    #[TestDox('Обновление хэша пароля')]
    public function testRehashPassword(): void
    {
        $this
            ->postJson('api/auth/login', [
                'email' => 'user@example.com',
                'password' => '123456',
            ])
            ->assertOk();

        /** @var User $user */
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user@example.com']);

        // Текущий хэш пароля
        $currentPasswordHash = $user->getAuthPassword();

        /**
         * Необходимо убедиться, что алгоритмическую сложность меняется
         *
         * @var int $bcryptRounds
         */
        $bcryptRounds = config('hashing.bcrypt.rounds');
        self::assertNotSame(10, $bcryptRounds);

        // Инициализируем драйвер с новой конфигурацией
        Hash::forgetDrivers();
        Config::set('hashing.bcrypt.rounds', 10);

        $this
            ->postJson('api/auth/login', [
                'email' => 'user@example.com',
                'password' => '123456',
            ])
            ->assertOk();

        /** @var User $user */
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user@example.com']);

        // Обновленный хэш пароля
        $updatedPasswordHash = $user->getAuthPassword();
        self::assertNotSame($currentPasswordHash, $updatedPasswordHash);

        // Успешный вход со старым паролем
        $this
            ->postJson('api/auth/login', [
                'email' => 'user@example.com',
                'password' => '123456',
            ])
            ->assertOk();
    }

    #[TestDox('Неправильный запрос')]
    public function testTooManyRequests(): void
    {
        $email = 'user@example.com';

        $this
            ->postJson('api/auth/login', [
                'email' => $email,
                'password' => '123456',
            ])
            ->assertOk();

        $response = fn (): TestResponse => $this
            ->postJson('api/auth/login', [
                'email' => $email,
                'password' => 'fakePassword',
            ]);

        Config::set('auth.rate_limiter_max_attempts.login', 1);

        ($response)()->assertUnauthorized();
        ($response)()->assertTooManyRequests();
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
                uri: 'api/auth/login',
                data: $body,
                headers: [ValidateOpenApiSchema::IGNORE_REQUEST_VALIDATE => true],
            )
            ->assertBadRequest();
    }

    public static function notValidRequestProvider(): Iterator
    {
        yield 'пустой запрос' => [['']];

        yield 'пустой email' => [['email' => '', 'password' => '123456']];

        yield 'невалидный email' => [['email' => 'fake', 'password' => '123456']];

        yield 'пустой пароль' => [['email' => 'user@example.com', 'password' => '']];
    }
}
