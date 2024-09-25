<?php

namespace Santosdave\PesawiseWrapper;

use Illuminate\Support\ServiceProvider;

class PesawiseProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/pesawise.php' => config_path('pesawise.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pesawise.php',
            'pesawise'
        );

        $this->app->singleton('pesawise', function ($app) {
            $config = $app['config']['pesawise'];
            return new Pesawise([
                'api_key' => $config['api_key'],
                'api_secret' => $config['api_secret'],
                'environment' => $config['environment'],
                'debug' => $config['debug'],
                'default_currency' => $config['default_currency'],
                'default_balance_id' => $config['default_balance_id'],
            ]);
        });
    }
}
