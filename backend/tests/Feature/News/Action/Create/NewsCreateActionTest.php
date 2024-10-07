<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\Create;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\FeatureTestCase;

/**
 * @internal
 */
#[TestDox('Ручка создания записи в новостях')]
final class NewsCreateActionTest extends FeatureTestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this->json('POST', 'api/news/create', ['title' => 'test']);

        $response->assertStatus(401);
    }
}
