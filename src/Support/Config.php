<?php

namespace Maize\CloudfrontCookies\Support;

use Carbon\CarbonInterval;
use DateInterval;
use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class Config
{
    public static function isEnabled(): bool
    {
        $enabled = config('cloudfront-cookies.enabled');

        if ($enabled === true) {
            return true;
        }

        if (trim($enabled) === 'true') {
            return true;
        }

        if (trim($enabled) === '1') {
            return true;
        }

        return false;
    }

    public static function getVersion(): string
    {
        return config('cloudfront-cookies.version') ?? 'latest';
    }

    public static function getRegion(): string
    {
        return config('cloudfront-cookies.region') ?? 'us-east-1';
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
        return throw_unless(
            config('cloudfront-cookies.cookie_domain'),
            Exception::class
        );
    }

    public static function getPrivateKey(): string
    {
        $keyPath = config('cloudfront-cookies.private_key') ?? storage_path('cloudfront-private.key');

        if (! file_exists($keyPath)) {
            throw new Exception("Private key file not found at: {$keyPath}");
        }

        return $keyPath;
    }

    public static function getKeyPairId(): string
    {
        return throw_unless(
            config('cloudfront-cookies.key_pair_id'),
            Exception::class
        );
    }

    private static function getExpirationInterval(): CarbonInterval
    {
        $expiration = config('cloudfront-cookies.expiration_interval');

        if (blank($expiration)) {
            return CarbonInterval::make('1 minutes');
        }

        if (is_string($expiration) || $expiration instanceof DateInterval) {
            return CarbonInterval::make($expiration);
        }

        throw new InvalidArgumentException;
    }

    public static function getExpiresAt(): int
    {
        return now()->add(
            self::getExpirationInterval()
        )->timestamp;
    }

    public static function getCookieDuration(): int
    {
        return (int) self::getExpirationInterval()->totalMinutes;
    }

    public static function getGuards(): array
    {
        $guards = config('cloudfront-cookies.guards');

        if (blank($guards)) {
            return [null];
        }

        if (is_string($guards) || is_array($guards)) {
            return Arr::wrap($guards);
        }

        throw new InvalidArgumentException;
    }
}
