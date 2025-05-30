<?php

namespace Maize\CloudfrontCookies\Support;

use Exception;

class Config
{
    public static function getVersion(): string
    {
        return config('cloudfront-cookies.version')
            ?? 'latest';
    }

    public static function getRegion(): string
    {
        return config('cloudfront-cookies.region')
            ?? 'us-east-1';
    }

    public static function getResourceKey(): string
    {
        return throw_unless(
            config('cloudfront-cookies.resource_key'),
            Exception::class
        );
    }

    public static function getCookieDomain(): string
    {
        // TODO: Starts with .
        return throw_unless(
            config('cloudfront-cookies.cookie_domain'),
            Exception::class
        );
    }

    public static function getPrivateKey(): string
    {
        return throw_unless(
            config('cloudfront-cookies.private_key'),
            Exception::class
        );
    }

    public static function getKeyPairId(): string
    {
        return throw_unless(
            config('cloudfront-cookies.key_pair_id'),
            Exception::class
        );
    }

    public static function getExpires()
    {
        return time() + 3000;
    }
}
