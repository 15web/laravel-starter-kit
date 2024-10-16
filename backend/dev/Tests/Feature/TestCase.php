<?php

declare(strict_types=1);

namespace Dev\Tests\Feature;

use App\Contract\Error;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * Базовый класс для тестов
 */
#[TestDox('Базовый класс для тестов')]
abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * @param non-empty-string $email
     * @param non-empty-string $password
     *
     * @return array{
     *     token: non-empty-string,
     *     roles: list<array{name: string, value: non-empty-string}>,
     *     email: non-empty-string
     * }
     */
    final protected function auth(
        string $email = 'user@example.com',
        string $password = '123456',
    ): array {
        /**
         * @var array{
         *     token: non-empty-string,
         *     roles: list<array{name: string, value: non-empty-string}>,
         *     email: non-empty-string
         * } $response
         */
        $response = $this
            ->postJson('api/auth/login', [
                'email' => $email,
                'password' => $password,
            ])
            ->assertOk()
            ->json();

        return $response;
    }

    final protected function beginDatabaseTransaction(): void
    {
        $this->getEntityManager()->getConnection()->beginTransaction();

        $this->beforeApplicationDestroyed(
            fn () => $this->getEntityManager()->getConnection()->rollBack(),
        );
    }

    final protected function getEntityManager(): EntityManager
    {
        return $this->app->make(EntityManager::class);
    }

    /**
     * @param non-empty-string $code
     * @param non-empty-string|null $message
     */
    final protected function assertApiError(TestResponse $response, string $code, ?string $message = null): void
    {
        $response
            ->assertOk()
            ->assertJson([
                'error' => true,
                'errorEnum' => Error::from($code)->value,
            ])
            ->when(filled($message), static fn (TestResponse $response) => $response->assertJson([
                'errorMessage' => $message,
            ]));
    }
}
