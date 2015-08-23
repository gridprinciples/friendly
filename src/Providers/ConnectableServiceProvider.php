<?php

namespace GridPrinciples\Connectable\Providers;

use GridPrinciples\Connectable\Providers\ConnectableAuthProvider;
use Illuminate\Support\ServiceProvider;

class ConnectableServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/connectable.php', 'connectable'
        );
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/connectable.php' => config_path('connectable.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../Migrations/' => database_path('migrations')
        ], 'migrations');
    }
}
