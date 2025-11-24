<?php

namespace Maize\CloudfrontCookies;

use Aws\CloudFront\CloudFrontClient;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Cookie;
use Maize\CloudfrontCookies\Support\Config;

class CloudfrontCookies
{
    public function __construct(
        private CloudFrontClient $client
    ) {}

    public function make(): array
    {
        return $this->client->getSignedCookie([
            'private_key' => Config::getPrivateKey(),
            'expires' => Config::getExpiresAt(),
            'key_pair_id' => Config::getKeyPairId(),
            'policy' => $this->getPolicy(),
        ]);
    }

    public function queue(): void
    {
        $cookies = $this->make();

        /** @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (method_exists(EncryptCookies::class, 'except')) {
            EncryptCookies::except(
                array_keys($cookies)
            );
        }

        foreach ($cookies as $name => $value) {
            Cookie::queue(
                name: $name,
                value: $value,
                minutes: Config::getCookieDuration(),
                path: '/',
                domain: Config::getCookieDomain(),
                secure: true,
            );
        }
    }

    public function clear(): void
    {
        $cookieNames = [
            'CloudFront-Policy',
            'CloudFront-Signature',
            'CloudFront-Key-Pair-Id',
        ];

        /** @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (method_exists(EncryptCookies::class, 'except')) {
            EncryptCookies::except($cookieNames);
        }

        foreach ($cookieNames as $name) {
            Cookie::queue(
                name: $name,
                value: '',
                minutes: -2628000,
                path: '/',
                domain: Config::getCookieDomain(),
                secure: true,
            );
        }
    }

    private function getPolicy(): string
    {
        return json_encode([
            'Statement' => [
                [
                    'Resource' => Config::getResourceKey(),
                    'Condition' => [
                        'DateLessThan' => [
                            'AWS:EpochTime' => Config::getExpiresAt(),
                        ],
                    ],
                ],
            ],
        ]);
    }
}
