<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pesawise API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Pesawise API credentials. These are used to
    | authenticate requests made to the Pesawise API.
    |
    */

    'api_key' => env('PESAWISE_API_KEY', ''),
    'api_secret' => env('PESAWISE_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Pesawise Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the package uses. Set this in your ".env" file.
    |
    */

    'environment' => env('PESAWISE_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When debug mode is enabled, detailed error messages with stack traces
    | will be shown on every error that occurs within your application.
    | If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('PESAWISE_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This determines the default currency used for transactions when not
    | explicitly specified.
    |
    */

    'default_currency' => env('PESAWISE_DEFAULT_CURRENCY', 'KES'),

    /*
    |--------------------------------------------------------------------------
    | Default Balance ID
    |--------------------------------------------------------------------------
    |
    | If you have a primary balance that you use frequently, you can set its
    | ID here to use as a default when not explicitly specified in requests.
    |
    */

    'default_balance_id' => env('PESAWISE_DEFAULT_BALANCE_ID', null),
];