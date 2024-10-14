<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Show;

use App\Infrastructure\ApiRequest\ApiRequest;
use Webmozart\Assert\Assert;

/**
 * Запрос для показа записи в блоге
 */
final readonly class ShowPostRequest implements ApiRequest
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
