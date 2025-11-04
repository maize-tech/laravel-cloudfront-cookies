<?php

namespace Maize\CloudfrontCookies;

use Aws\CloudFront\CloudFrontClient;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Maize\CloudfrontCookies\Listeners\ClearCloudfrontCookiesOnLogout;
use Maize\CloudfrontCookies\Support\Config;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudfrontCookiesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloudfront-cookies')
            ->hasConfigFile()
            ->hasInstallCommand(fn (InstallCommand $command) => (
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('maize-tech/laravel-cloudfront-cookies')
            ));
    }

    public function packageBooted(): void
    {
        $this->app->singleton(CloudFrontClient::class, fn () => (
            new CloudFrontClient([
                'version' => Config::getVersion(),
                'region' => Config::getRegion(),
            ])
        ));

        // Register logout event listener
        Event::listen(
            Logout::class,
            ClearCloudfrontCookiesOnLogout::class
        );
    }
}
