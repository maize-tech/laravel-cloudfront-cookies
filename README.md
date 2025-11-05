# Laravel CloudFront Cookies

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-cloudfront-cookies.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-cloudfront-cookies)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-cloudfront-cookies/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/maize-tech/laravel-cloudfront-cookies/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-cloudfront-cookies/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/maize-tech/laravel-cloudfront-cookies/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-cloudfront-cookies.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-cloudfront-cookies)

A Laravel package to easily manage AWS CloudFront signed cookies for authenticated users. This package automatically generates and manages CloudFront signed cookies, allowing you to restrict access to your CloudFront distributions based on user authentication status. Cookies are automatically set for authenticated users and cleared on logout.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-cloudfront-cookies
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-cloudfront-cookies-config"
```

Or use the install command:

```bash
php artisan cloudfront-cookies:install
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable CloudFront signed cookies.
    | If not set, the package will default to disabled (false).
    |
    | When disabled, cookies will not be set even if the middleware is active.
    |
    */
    'enabled' => env('CLOUDFRONT_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | CloudFront API Version
    |--------------------------------------------------------------------------
    |
    | The version of the CloudFront API to use.
    | If not set, the package will use 'latest' for the most recent version.
    |
    | You can override this by setting a specific API version (e.g., '2020-05-31').
    |
    */
    'version' => null,

    /*
    |--------------------------------------------------------------------------
    | AWS Region
    |--------------------------------------------------------------------------
    |
    | The AWS region for CloudFront API calls.
    | If not set, the package will use 'us-east-1' as the default region.
    |
    | CloudFront is a global service but requires a region for API calls.
    |
    */
    'region' => null,

    /*
    |--------------------------------------------------------------------------
    | Resource Key
    |--------------------------------------------------------------------------
    |
    | The CloudFront resource URL pattern that the signed cookies will grant
    | access to.
    |
    | This value is required. It should match your CloudFront distribution
    | URL pattern (e.g., 'https://d111111abcdef8.cloudfront.net/*').
    |
    */
    'resource_key' => env('CLOUDFRONT_RESOURCE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Domain
    |--------------------------------------------------------------------------
    |
    | The domain for which the signed cookies will be valid.
    |
    | This value is required. It should start with a dot (.) to include
    | all subdomains (e.g., '.example.com').
    |
    */
    'cookie_domain' => env('CLOUDFRONT_COOKIE_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Private Key
    |--------------------------------------------------------------------------
    |
    | The path to the CloudFront private key file used to sign the cookies.
    | If not set, the package will look for the key file at:
    | storage_path('cloudfront-private.key')
    |
    | You can override this by setting the path to your private key file.
    |
    */
    'private_key' => null,

    /*
    |--------------------------------------------------------------------------
    | Key Pair ID
    |--------------------------------------------------------------------------
    |
    | The ID of the CloudFront key pair associated with your private key.
    |
    | This value is required. It identifies which key pair CloudFront should
    | use to validate the signed cookies.
    |
    */
    'key_pair_id' => env('CLOUDFRONT_KEY_PAIR_ID'),

    /*
    |--------------------------------------------------------------------------
    | Expiration Interval
    |--------------------------------------------------------------------------
    |
    | The duration for which both the signed cookie policy and browser cookies
    | will be valid.
    | If not set, the package will use '1 minutes' as the default duration.
    |
    | Accepted formats:
    | - String: human-readable format like '1 hour', '30 minutes', '1 day'
    | - DateInterval: PHP DateInterval instance
    | - CarbonInterval: Carbon interval instance
    |
    */
    'expiration_interval' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | The authentication guard to use when checking if a user is authenticated
    | before setting CloudFront cookies.
    | If not set, the package will use the default authentication guard.
    |
    | You can override this by setting a specific guard name (e.g., 'api').
    |
    */
    'guard' => null,
];
```

## Usage

Add the `SignCloudfrontCookies` middleware to your routes or route groups:

```php
use Maize\CloudfrontCookies\Http\Middleware\SignCloudfrontCookies;

Route::middleware(['auth', SignCloudfrontCookies::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

**Note for Laravel versions prior to 11**: You need to manually exclude CloudFront cookies from encryption. Add the following to your `app/Http/Middleware/EncryptCookies.php`:

```php
protected $except = [
    'CloudFront-Policy',
    'CloudFront-Signature',
    'CloudFront-Key-Pair-Id',
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Enrico De Lazzari](https://github.com/enricodelazzari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
