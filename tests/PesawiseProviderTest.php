<?php

namespace Santosdave\PesawiseWrapper\Tests;

use Orchestra\Testbench\TestCase;
use Santosdave\PesawiseWrapper\Pesawise;
use Santosdave\PesawiseWrapper\PesawiseProvider;


class PesawiseProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PesawiseProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('pesawise.api_key', 'test_api_key');
        $app['config']->set('pesawise.api_secret', 'test_api_secret');
        $app['config']->set('pesawise.environment', 'sandbox');
    }

    public function testServiceIsRegistered()
    {
        $this->assertTrue($this->app->bound('pesawise'));
        $this->assertInstanceOf(Pesawise::class, $this->app->make('pesawise'));
    }

    public function testConfigIsPublished()
    {
        $this->artisan('vendor:publish', ['--provider' => 'Santosdave\PesawiseWrapper\PesawiseProvider']);
        $this->assertFileExists(config_path('pesawise.php'));
    }
}