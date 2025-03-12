<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Infrastructure\Request;

use Dev\Tests\Feature\TestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Заголовок Accept')]
final class AcceptHeaderTest extends TestCase
{
    /**
     * @param array<string, string> $headers
     */
    #[DataProvider('validHeader')]
    #[TestDox('Допустимые заголовки')]
    public function testValidHeaders(array $headers): void
    {
        $response = $this->getJson(
            uri: 'api/ping',
            headers: $headers,
        );

        $response->assertOk();
    }

    public static function validHeader(): Iterator
    {
        yield 'Пустые заголовки' => [[]];

        yield 'Любой MIME тип' => [['Accept' => '*/*']];

        yield 'Accept application/*' => [['Accept' => 'application/*']];

        yield 'Accept application/json' => [['Accept' => 'application/json']];

        yield 'Accept text/html and application/json' => [['Accept' => 'text/html, application/json']];
    }

    /**
     * @param array<string, string> $headers
     */
    #[DataProvider('notValidAcceptHeader')]
    #[TestDox('Недопустимые заголовки Accept')]
    public function testInvalidHeaders(array $headers): void
    {
        $response = $this->getJson(
            uri: 'api/ping',
            headers: $headers,
        );

        $response->assertBadRequest();
    }

    public static function notValidAcceptHeader(): Iterator
    {
        yield 'Accept text/html' => [['Accept' => 'text/html']];

        yield 'Accept application/xml' => [['Accept' => 'application/xml']];
    }
}
