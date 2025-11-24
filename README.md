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

You can install and configure the package with:

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
    'guards' => [],
];
```

## AWS CloudFront Setup

Before using this package, you need to configure AWS CloudFront with signed cookies. This guide assumes you already have an S3 bucket with your assets and a domain name hosted on the internet.

### 1. Generate Public and Private Keys

Create a new RSA private key:

```bash
openssl genrsa -out cloudfront-private.key 2048
```

Extract the public key:

```bash
openssl rsa -pubout -in cloudfront-private.key -out cloudfront-public.key
```

**Important**: Store the `cloudfront-private.key` file in your Laravel application's `storage` directory. This is the default location the package looks for the private key.

### 2. Create a CloudFront Key Group

1. Go to the AWS CloudFront console
2. Navigate to **Key management** > **Public keys**
3. Click **Create public key**
4. Give it a name (e.g., "My App Public Key")
5. Paste the content of `cloudfront-public.key`
6. Save and note down the **Key ID** (you'll need this for `CLOUDFRONT_KEY_PAIR_ID`)
7. Navigate to **Key groups** and create a new key group
8. Add the public key you just created to this key group

### 3. Create a CloudFront Distribution

**Important**: Your CloudFront distribution must use the same root domain as your application. For example, if your application is at `example.com`, your CloudFront domain should be something like `assets.example.com` or `cdn.example.com`. This is necessary because cookies can only be set for domains you own.

1. Go to the CloudFront console and click **Create distribution**
2. Under **Origin domain**, select your S3 bucket with the assets
3. Under **Default cache behavior**:
   - Set **Restrict viewer access** to **Yes**
   - Select the key group you created earlier
4. Under **Settings**:
   - Add your custom SSL certificate (e.g., `*.example.com`)
   - Under **Alternate domain name (CNAME)**, add your CloudFront domain (e.g., `cdn.example.com`)
5. Create the distribution (this may take 10-15 minutes to deploy)
6. Note down the **Distribution domain name** (e.g., `d1234abcd.cloudfront.net`) and the full CloudFront URL (e.g., `https://d1234abcd.cloudfront.net/*`)

### 4. Configure Route 53 DNS

1. Go to Route 53 and select your hosted zone
2. Click **Create record**
3. Set the record name to match your CloudFront CNAME (e.g., `cdn`)
4. Enable the **Alias** toggle
5. Select **CloudFront distribution** as the alias target
6. Select your distribution from the dropdown
7. Create the record

If you don't see your distribution in the dropdown, you can use a CNAME record type instead and use the CloudFront domain name as the value.

### 5. Update S3 Bucket CORS Policy

1. Go to your S3 bucket
2. Navigate to the **Permissions** tab
3. Scroll to the **Cross-origin resource sharing (CORS)** section
4. Update the policy (replace `example.com` and `cdn.example.com` with your domains):

```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "HEAD"],
        "AllowedOrigins": [
            "https://example.com",
            "https://cdn.example.com"
        ],
        "ExposeHeaders": ["ETag"]
    }
]
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
