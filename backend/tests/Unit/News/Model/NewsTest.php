<?php

declare(strict_types=1);

namespace Tests\Unit\News\Model;

use App\Module\News\Model\News;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[TestDox('Создание записи в новостях')]
final class NewsTest extends TestCase
{
    #[TestDox('Успешный запрос')]
    public function testCreate(): void
    {
        $title = 'Новость дня';

        $news = new News($title);

        self::assertSame($title, $news->getTitle());
    }
}
