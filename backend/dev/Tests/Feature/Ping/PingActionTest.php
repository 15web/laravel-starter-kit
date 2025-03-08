<?php

declare(strict_types=1);

namespace Dev\Tests\Feature\Ping;

use DateTimeImmutable;
use Dev\Tests\Feature\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[TestDox('Пинг')]
final class PingActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceedRequest(): void
    {
        $response = $this
            ->getJson('api/ping')
            ->assertOk();

        /**
         * @var array{
         *     result: non-empty-string,
         *     now: non-empty-string
         * } $data
         */
        $data = $response->json('data');

        self::assertSame('pong', $data['result']);
        self::assertInstanceOf(
            DateTimeImmutable::class,
            DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $data['now']),
        );
    }
}
