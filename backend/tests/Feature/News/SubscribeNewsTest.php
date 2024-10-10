<?php

declare(strict_types=1);

namespace News;

use App\Module\News\Notification\Subscribe;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

/**
 * @internal
 */
#[TestDox('Ручка подписки на новости')]
final class SubscribeNewsTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSucceed(): void
    {
        Mail::fake();

        $this
            ->post('/api/news/subscribe', ['email' => 'user@example.com'])
            ->assertOk();

        Mail::assertQueued(Subscribe::class, ['user@example.com']);
    }
}
