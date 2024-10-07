<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\List;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\FeatureTestCase;

/**
 * @internal
 */
#[TestDox('TODO: опиши что проверяется')]
final class NewsListActionTest extends FeatureTestCase
{
    #[TestDox('TODO: опиши что проверяется')]
    public function testSuccess(): void
    {
        $response = $this->json('GET', 'api/news/list');

        $response->assertStatus(401);
    }
}
