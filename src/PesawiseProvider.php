<?php

namespace Santosdave\PesawiseWrapper;

use Illuminate\Support\ServiceProvider;

class PesawiseProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/pesawise.php' => config_path('pesawise.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pesawise.php', 'pesawise');

        $this->app->singleton('pesawise', function ($app) {
            return new Pesawise([
                'apiKey' => $app['config']['pesawise.api_key'],
                'apiSecret' => $app['config']['pesawise.api_secret'],
                'env' => $app['config']['pesawise.environment'],
                'debug' => $app['config']['pesawise.debug'],
                'defaultCurrency' => $app['config']['pesawise.default_currency'],
                'defaultBalanceId' => $app['config']['pesawise.default_balance_id'],
            ]);
        });
    }
}