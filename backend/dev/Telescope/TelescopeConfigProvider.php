<?php

declare(strict_types=1);

namespace Dev\Telescope;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;
use Override;

/**
 * Конфигуратор Telescope
 */
final class TelescopeConfigProvider extends TelescopeApplicationServiceProvider
{
    #[Override]
    public function register(): void
    {
        Telescope::filter(static fn (IncomingEntry $entry): bool => true);

        $this->hideSensitiveRequestDetails();
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    #[Override]
    protected function gate(): void
    {
        Gate::define('viewTelescope', static fn (): bool => true);
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    private function hideSensitiveRequestDetails(): void
    {
        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);

        Telescope::hideResponseParameters(['data.token']);
    }
}
