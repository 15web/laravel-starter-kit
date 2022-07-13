<?php

namespace Tests\Unit\News\Model;

use App\Module\News\Model\News;
use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    public function testCreate(): void
    {
        $title = 'Новость дня';

        $news = new News($title);

        $this->assertSame($title, $news->getTitle());
    }
}
