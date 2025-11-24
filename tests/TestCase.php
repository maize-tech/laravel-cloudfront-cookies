<?php

namespace Maize\CloudfrontCookies\Tests;

use Illuminate\Support\Facades\Route;
use Maize\CloudfrontCookies\CloudfrontCookiesServiceProvider;
use Maize\CloudfrontCookies\Http\Middleware\SignCloudfrontCookies;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CloudfrontCookiesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        Route::middleware([SignCloudfrontCookies::class])
            ->get('/', fn () => 'ok');
    }
}
