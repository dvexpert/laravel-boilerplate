<?php

namespace Boilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use Boilerplate\Console\Commands\BoilerplateInstallCommand;

class BoilerplateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BoilerplateInstallCommand::class,
            ]);
        }
    }
}
