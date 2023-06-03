<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Module\Filter\Contract\SearchFilter;
use App\Module\Filter\Filters\Filter1;
use App\Module\Filter\Filters\Filter2;
use Illuminate\Support\ServiceProvider;

final class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Filter1\Filter::class);
        $this->app->bind(Filter2\Filter::class);

        $this->app->when(FilterAggregator::class)
            ->needs(SearchFilter::class)
            ->give(static function ($app) {
                return [
                    $app->make(Filter1\Filter::class),
                    $app->make(Filter2\Filter::class),
                ];
            });
    }
}
