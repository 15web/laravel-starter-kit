<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\List;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\TestCase;

/**
 * @internal
 */
#[TestDox('Ручка получения списка новостей')]
final class NewsListActionTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testSuccess(): void
    {
        $response = $this->getJson('api/news/list');

        $response->assertUnauthorized();
    }
}
