<?php

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Date;
use Maize\CloudfrontCookies\Support\Config;

beforeEach(function () {
    Date::setTestNow('2025-01-01 12:00:00');
});

afterEach(function () {
    Date::setTestNow();
});

describe('getVersion', function () {
    it('returns default version when not configured', function () {
        expect(Config::getVersion())->toBe('latest');
    });

    it('returns configured version', function () {
        config()->set('cloudfront-cookies.version', '2020-05-31');

        expect(Config::getVersion())->toBe('2020-05-31');
    });
});

describe('getRegion', function () {
    it('returns default region when not configured', function () {
        expect(Config::getRegion())->toBe('us-east-1');
    });

    it('returns configured region', function () {
        config()->set('cloudfront-cookies.region', 'eu-west-1');

        expect(Config::getRegion())->toBe('eu-west-1');
    });
});

describe('getResourceKey', function () {
    it('throws exception when not configured', function () {
        Config::getResourceKey();
    })->throws(\Exception::class);

    it('returns configured resource key', function () {
        config()->set('cloudfront-cookies.resource_key', 'https://d111111abcdef8.cloudfront.net/*');

        expect(Config::getResourceKey())->toBe('https://d111111abcdef8.cloudfront.net/*');
    });
});

describe('getCookieDomain', function () {
    it('throws exception when not configured', function () {
        Config::getCookieDomain();
    })->throws(\Exception::class);

    it('returns configured cookie domain', function () {
        config()->set('cloudfront-cookies.cookie_domain', '.example.com');

        expect(Config::getCookieDomain())->toBe('.example.com');
    });
});

describe('getPrivateKey', function () {
    it('returns default path when not configured', function () {
        config()->set('cloudfront-cookies.private_key', null);

        expect(config('cloudfront-cookies.private_key') ?? storage_path('cloudfront-private.key'))
            ->toBe(storage_path('cloudfront-private.key'));
    });

    it('returns configured private key path', function () {
        $keyPath = '/path/to/cloudfront-private.key';
        config()->set('cloudfront-cookies.private_key', $keyPath);

        expect(config('cloudfront-cookies.private_key'))->toBe($keyPath);
    });
});

describe('getKeyPairId', function () {
    it('throws exception when not configured', function () {
        Config::getKeyPairId();
    })->throws(\Exception::class);

    it('returns configured key pair id', function () {
        config()->set('cloudfront-cookies.key_pair_id', 'APKAEXAMPLE');

        expect(Config::getKeyPairId())->toBe('APKAEXAMPLE');
    });
});

describe('getExpiresAt', function () {
    it('returns default expiration timestamp (1 minute from now)', function () {
        $expectedTimestamp = Date::now()->addMinute()->timestamp;

        expect(Config::getExpiresAt())->toBe($expectedTimestamp);
    });

    it('returns custom expiration timestamp', function () {
        config()->set('cloudfront-cookies.expiration_interval', '7 days');

        $expectedTimestamp = Date::now()->addDays(7)->timestamp;

        expect(Config::getExpiresAt())->toBe($expectedTimestamp);
    });

    it('handles different interval types', function () {
        config()->set('cloudfront-cookies.expiration_interval', '2 hours');

        $expectedTimestamp = Date::now()->addHours(2)->timestamp;

        expect(Config::getExpiresAt())->toBe($expectedTimestamp);
    });

    it('handles week interval', function () {
        config()->set('cloudfront-cookies.expiration_interval', '1 week');

        $expectedTimestamp = Date::now()->addWeek()->timestamp;

        expect(Config::getExpiresAt())->toBe($expectedTimestamp);
    });
});

describe('getCookieDuration', function () {
    it('returns default duration in minutes (1 minute)', function () {
        expect(Config::getCookieDuration())->toBe(1);
    });

    it('returns custom duration in minutes', function () {
        config()->set('cloudfront-cookies.expiration_interval', '7 days');

        expect(Config::getCookieDuration())->toBe(10080); // 7 days * 24 hours * 60 minutes
    });

    it('handles hour intervals', function () {
        config()->set('cloudfront-cookies.expiration_interval', '2 hours');

        expect(Config::getCookieDuration())->toBe(120); // 2 hours * 60 minutes
    });

    it('handles minute intervals', function () {
        config()->set('cloudfront-cookies.expiration_interval', '45 minutes');

        expect(Config::getCookieDuration())->toBe(45);
    });

    it('handles week intervals', function () {
        config()->set('cloudfront-cookies.expiration_interval', '2 weeks');

        expect(Config::getCookieDuration())->toBe(20160); // 2 weeks * 7 days * 24 hours * 60 minutes
    });

    it('handles DateInterval instances', function () {
        config()->set('cloudfront-cookies.expiration_interval', CarbonInterval::days(5));

        expect(Config::getCookieDuration())->toBe(7200); // 5 days * 24 hours * 60 minutes
    });
});

describe('invalid config values', function () {
    it('throws InvalidArgumentException when expiration_interval is an integer', function () {
        config()->set('cloudfront-cookies.expiration_interval', 123);

        Config::getExpiresAt();
    })->throws(\InvalidArgumentException::class);

    it('throws InvalidArgumentException when expiration_interval is an array', function () {
        config()->set('cloudfront-cookies.expiration_interval', ['days' => 30]);

        Config::getExpiresAt();
    })->throws(\InvalidArgumentException::class);

    it('throws InvalidArgumentException when expiration_interval is a boolean', function () {
        config()->set('cloudfront-cookies.expiration_interval', true);

        Config::getCookieDuration();
    })->throws(\InvalidArgumentException::class);
});
