<?php

declare(strict_types=1);

namespace App\Blog\Http\Show;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос для показа записи в блоге
 */
final readonly class ShowPostRequest implements Request
{
    /**
     * @param non-empty-string $title Заголовок записи
     */
    public function __construct(
        public string $title,
    ) {
        Assert::stringNotEmpty($this->title);
    }
}
