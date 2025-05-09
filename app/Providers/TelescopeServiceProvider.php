<?php

namespace App\Providers;

use App\Helper\Helper;
use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        Telescope::filter(fn (IncomingEntry $incomingEntry): bool => $isLocal  // phpcs:ignore
            || $incomingEntry->isReportableException() // phpcs:ignore
            || $incomingEntry->isFailedRequest()       // phpcs:ignore
            || $incomingEntry->isFailedJob()           // phpcs:ignore
            || $incomingEntry->isScheduledTask()       // phpcs:ignore
            || $incomingEntry->hasMonitoredTag());     // phpcs:ignore
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user): bool {
            // TODO: Need to update this with Role check when roles/Permission is Implemented.
            // @codeCoverageIgnoreStart
            return Helper::isDebugAuthUser($user);
            // @codeCoverageIgnoreEnd
            // return in_array($user->email, []);
        });
    }
}
