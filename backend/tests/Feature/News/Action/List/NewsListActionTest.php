<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\List;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\FeatureTestCase;

/**
 * @internal
 */
#[TestDox('Ручка получения списка новостей')]
final class NewsListActionTest extends FeatureTestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this->json('GET', 'api/news/list');

        $response->assertStatus(401);
    }
}
