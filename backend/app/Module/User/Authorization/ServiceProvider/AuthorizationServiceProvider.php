<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\ServiceProvider;

use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class AuthorizationServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(DenyUnlessUserHasRole::class);
    }
}
