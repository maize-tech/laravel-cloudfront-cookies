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
            'expires' => Config::getExpires(),
            'key_pair_id' => Config::getKeyPairId(),
            'policy' => static::getPolicy(),
        ]);
    }

    public function queue(): void
    {
        $cookies = $this->make();

        EncryptCookies::except(
            array_keys($cookies)
        );

        $host = request()->host(); // TODO

        foreach ($cookies as $name => $value) {

            // TODO
            Cookie::queue(
                name: $name,
                value: $value,
                minutes: 60 * 24 * 30,
                path: '/',
                domain: ".{$host}",
                secure: true,
            );
        }
    }

    private static function getPolicy(): string
    {
        return json_encode([
            'Statement' => [
                [
                    'Resource' => Config::getResourceKey(),
                    'Condition' => [
                        'DateLessThan' => [
                            'AWS:EpochTime' => Config::getExpires(),
                        ],
                    ],
                ],
            ],
        ]);
    }
}
