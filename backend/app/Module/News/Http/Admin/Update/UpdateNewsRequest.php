<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Update;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос на обновление новости
 */
final readonly class UpdateNewsRequest implements Request
{
    /**
     * @param non-empty-string $title Заголовок новости
     */
    public function __construct(
        public string $title,
    ) {
        Assert::stringNotEmpty($title);
    }
}
