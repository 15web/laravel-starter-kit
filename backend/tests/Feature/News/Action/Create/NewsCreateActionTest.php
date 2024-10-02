<?php

declare(strict_types=1);

namespace Tests\Feature\News\Action\Create;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\FeatureTestCase;

/**
 * @internal
 */
#[TestDox('TODO: опиши что проверяется')]
final class NewsCreateActionTest extends FeatureTestCase
{
    #[TestDox('TODO: опиши что проверяется')]
    public function testSuccess(): void
    {
        $response = $this->json('POST', 'api/news/create', ['title' => 'test']);

        $response->assertStatus(401);
    }
}
