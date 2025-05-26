<?php

namespace Maize\CloudfrontCookies;

use Aws\CloudFront\CloudFrontClient;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudfrontCookiesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloudfront-cookies')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        $this->app->singleton(CloudFrontClient::class, function () {
            return new CloudFrontClient([
                'version' => config('cloudfront.version'),
                'region' => config('cloudfront.region'),
            ]);
        });
    }
}
