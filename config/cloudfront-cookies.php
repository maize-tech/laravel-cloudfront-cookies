<?php

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
