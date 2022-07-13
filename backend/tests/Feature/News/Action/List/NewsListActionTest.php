<?php

namespace Tests\Feature\News\Action\List;

use Tests\Feature\FeatureTestCase;

class NewsListActionTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
        $response = $this->call('GET', 'api/news/list');

        $response->assertStatus(200);
    }
}
