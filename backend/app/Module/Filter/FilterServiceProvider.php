<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Module\Filter\Filter1\Filter1;
use App\Module\Filter\Filter2\Filter2;
use Illuminate\Support\ServiceProvider;

final class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Filter1::class);
        $this->app->bind(Filter2::class);

        $this->app->when(FilterAggregator::class)
            ->needs(SearchFilter::class)
            ->give(static function ($app) {
                return [
                    $app->make(Filter1::class),
                    $app->make(Filter2::class),
                ];
            });
    }
}
