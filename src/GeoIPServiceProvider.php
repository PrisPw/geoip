<?php

namespace PrisPW\GeoIP;

use Illuminate\Support\ServiceProvider;
use PrisPW\GeoIP\Console\UpdateCommand;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->app['geoip'] = function ($app) {
            return $app['PrisPW\GeoIP\GeoIP'];
        };

        if ($this->app->runningInConsole()) {
            $this->commands(['PrisPW\GeoIP\Console\UpdateCommand']);
        }

        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/geoip.php' => config_path('geoip.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geoip.php', 'geoip');

        $this->registerGeoIP();

        $this->registerUpdateCommand();
    }

    /**
     * Register the main geoip wrapper.
     */
    protected function registerGeoIP()
    {
        $this->app->singleton('PrisPW\GeoIP\GeoIP', function ($app) {
            return new GeoIP($app['config']['geoip']);
        });
    }

    /**
     * Register the geoip update console command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton('PrisPW\GeoIP\Console\UpdateCommand', function ($app) {
            return new UpdateCommand($app['config']['geoip']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'PrisPW\GeoIP\GeoIP',
            'PrisPW\GeoIP\Console\UpdateCommand',
            'geoip',
        ];
    }
}
