<?php

namespace Tests\Feature\News\Action\Create;

use Tests\Feature\FeatureTestCase;

class NewsCreateActionTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
        $response = $this->call('GET', 'api/news/create?title=test');

        $response->assertStatus(200);
    }
}
