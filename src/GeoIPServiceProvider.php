<?php

namespace PrisPW\GeoIP;

use Illuminate\Support\ServiceProvider;

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
            'geoip',
        ];
    }
}
