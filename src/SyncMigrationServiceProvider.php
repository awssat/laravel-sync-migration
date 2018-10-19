<?php

namespace Awssat\SyncMigration;

use Illuminate\Support\ServiceProvider;

class SyncMigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.devMigrateCommand', function ($app) {
            return new SyncMigrateCommand($app['migrator']);
        });

        $this->commands([
            'command.devMigrateCommand',
        ]);
    }
}
