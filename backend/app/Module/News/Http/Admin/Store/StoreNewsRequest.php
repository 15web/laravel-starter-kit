<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Store;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос создания новости
 */
final readonly class StoreNewsRequest implements Request
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