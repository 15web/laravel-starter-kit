<?php

declare(strict_types=1);

namespace App\Module\Blog;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class BlogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lang::addNamespace('blog', __DIR__.'/lang');
    }
}
