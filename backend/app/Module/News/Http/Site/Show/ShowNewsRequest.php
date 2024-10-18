<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Show;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос для просмотра новости
 */
final readonly class ShowNewsRequest implements Request
{
    /**
     * @param non-empty-string $title
     */
    public function __construct(
        public string $title,
    ) {
        Assert::stringNotEmpty($this->title);
    }
}
