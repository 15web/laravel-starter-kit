<?php

declare(strict_types=1);

namespace App\Module\Blog;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

final class BlogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lang::addNamespace('blog', __DIR__.'/lang');
    }
}
