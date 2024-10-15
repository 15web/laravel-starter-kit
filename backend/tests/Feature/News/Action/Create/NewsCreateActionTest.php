<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\Create;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

/**
 * @internal
 */
#[TestDox('Ручка создания записи в новостях')]
final class NewsCreateActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this->postJson('api/news/create', ['title' => 'test']);

        $response->assertUnauthorized();
    }
}
