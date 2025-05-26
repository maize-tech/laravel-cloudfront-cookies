<?php

namespace Maize\CloudfrontCookies;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Maize\CloudfrontCookies\Commands\CloudfrontCookiesCommand;

class CloudfrontCookiesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cloudfront-cookies')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_cloudfront_cookies_table')
            ->hasCommand(CloudfrontCookiesCommand::class);
    }
}
