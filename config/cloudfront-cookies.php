<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable CloudFront signed cookies. When disabled, cookies will
    | not be set even if the middleware is active.
    |
    | Default: true
    |
    */
    'enabled' => env('CLOUDFRONT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | CloudFront API Version
    |--------------------------------------------------------------------------
    |
    | The version of the CloudFront API to use. Use 'latest' for the most
    | recent version.
    | Default: 'latest'
    |
    */
    'version' => null,

    /*
    |--------------------------------------------------------------------------
    | AWS Region
    |--------------------------------------------------------------------------
    |
    | The AWS region where your CloudFront distribution is configured.
    | CloudFront is a global service but requires a region for API calls.
    | Default: 'us-east-1'
    |
    */
    'region' => null,

    /*
    |--------------------------------------------------------------------------
    | Resource Key
    |--------------------------------------------------------------------------
    |
    | The CloudFront resource URL pattern that the signed cookies will grant
    | access to. This should match your CloudFront distribution URL pattern.
    | Example: 'https://d111111abcdef8.cloudfront.net/*'
    |
    */
    'resource_key' => env('CLOUDFRONT_RESOURCE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Domain
    |--------------------------------------------------------------------------
    |
    | The domain for which the signed cookies will be valid. This should
    | start with a dot (.) to include all subdomains.
    | Example: '.example.com'
    |
    */
    'cookie_domain' => env('CLOUDFRONT_COOKIE_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Private Key
    |--------------------------------------------------------------------------
    |
    | The CloudFront private key used to sign the cookies.
    |
    */
    'private_key' => env('CLOUDFRONT_PRIVATE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Key Pair ID
    |--------------------------------------------------------------------------
    |
    | The ID of the CloudFront key pair associated with your private key.
    |
    */
    'key_pair_id' => env('CLOUDFRONT_KEY_PAIR_ID'),

    /*
    |--------------------------------------------------------------------------
    | Expiration Interval
    |--------------------------------------------------------------------------
    |
    | The duration for which both the signed cookie policy and browser cookies
    | will be valid. This value is used for both CloudFront policy expiration
    | and browser cookie duration.
    |
    | Accepts:
    | - String: human-readable format like '1 hour', '30 minutes', '1 day'
    | - DateInterval: PHP DateInterval instance
    | - CarbonInterval: Carbon interval instance
    |
    | Default: '1 minutes'
    |
    */
    'expiration_interval' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | The authentication guard to use when checking if a user is authenticated
    | before setting CloudFront cookies. Set to null to use the default guard.
    |
    | Default: null (uses default guard)
    |
    */
    'guard' => null,
];
