<?php

declare(strict_types=1);

namespace App\Module\Filter;

use App\Module\Filter\Contract\SearchFilter;
use App\Module\Filter\Filters\Filter1\Filter;
use App\Module\Filter\Filters\Filter2;
use Illuminate\Support\ServiceProvider;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Filter::class);
        $this->app->bind(Filter2\Filter::class);

        $this->app->when(FilterAggregator::class)
            ->needs(SearchFilter::class)
            ->give(static fn ($app): array => [
                $app->make(Filter::class),
                $app->make(Filter2\Filter::class),
            ]);
    }
}
