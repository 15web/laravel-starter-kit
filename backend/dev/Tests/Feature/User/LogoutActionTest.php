<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\User;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Ручка выхода')]
final class LogoutActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $auth = $this->auth();
        $response = $this
            ->withToken($auth['token'])
            ->postJson('api/auth/logout')
            ->assertOk();

        /**
         * @var array{
         *      success: bool
         *  } $data
         */
        $data = $response->json();

        self::assertTrue($data['success']);
    }

    #[TestDox('Запрос без авторизации')]
    public function testUnauthorized(): void
    {
        $this
            ->postJson('api/auth/logout', [ValidateOpenApiSchema::VALIDATE_REQUEST_KEY => false])
            ->assertUnauthorized();
    }
}