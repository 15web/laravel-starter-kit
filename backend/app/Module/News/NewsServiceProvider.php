<?php

declare(strict_types=1);

namespace App\Module\News;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

/**
 * Сервис провайдер модуля "Новости"
 */
final class NewsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lang::addNamespace('news', __DIR__.'/lang');
    }
}
