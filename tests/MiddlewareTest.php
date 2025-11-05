<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Maize\CloudfrontCookies\Http\Middleware\SignCloudfrontCookies;
use Maize\CloudfrontCookies\Support\Config;

beforeEach(function () {
    config()->set('cloudfront-cookies.enabled', true);
    config()->set('cloudfront-cookies.resource_key', 'https://d111111abcdef8.cloudfront.net/*');
    config()->set('cloudfront-cookies.cookie_domain', '.example.com');
    config()->set('cloudfront-cookies.private_key', '/path/to/cloudfront-private.key');
    config()->set('cloudfront-cookies.key_pair_id', 'APKAEXAMPLE');
});

describe('SignCloudfrontCookies middleware', function () {
    it('sets cookies when user is authenticated', function () {
        config()->set('cloudfront-cookies.enabled', true);

        Auth::shouldReceive('guard')
            ->with(null)
            ->andReturnSelf();

        Auth::shouldReceive('check')
            ->andReturn(true);

        \Maize\CloudfrontCookies\Facades\CloudfrontCookies::shouldReceive('queue')
            ->once();

        $middleware = new SignCloudfrontCookies;
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        expect($response->getStatusCode())->toBe(200);
    });

    it('does not set cookies when user is not authenticated', function () {
        config()->set('cloudfront-cookies.enabled', true);

        Auth::shouldReceive('guard')
            ->with(null)
            ->andReturnSelf();

        Auth::shouldReceive('check')
            ->andReturn(false);

        Cookie::shouldReceive('queue')->never();

        $middleware = new SignCloudfrontCookies;
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        expect($response->getStatusCode())->toBe(200);
    });

    it('does not set cookies when disabled', function () {
        config()->set('cloudfront-cookies.enabled', false);

        Auth::shouldReceive('guard')->never();
        Auth::shouldReceive('check')->never();
        Cookie::shouldReceive('queue')->never();

        $middleware = new SignCloudfrontCookies;
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        expect($response->getStatusCode())->toBe(200);
    });

    it('uses custom guard when configured', function () {
        config()->set('cloudfront-cookies.enabled', true);
        config()->set('cloudfront-cookies.guard', 'api');

        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturnSelf();

        Auth::shouldReceive('check')
            ->andReturn(true);

        \Maize\CloudfrontCookies\Facades\CloudfrontCookies::shouldReceive('queue')
            ->once();

        $middleware = new SignCloudfrontCookies;
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        expect($response->getStatusCode())->toBe(200);
    });
});

describe('Config isEnabled', function () {
    it('returns true when enabled is true', function () {
        config()->set('cloudfront-cookies.enabled', true);

        expect(Config::isEnabled())->toBe(true);
    });

    it('returns false when enabled is false', function () {
        config()->set('cloudfront-cookies.enabled', false);

        expect(Config::isEnabled())->toBe(false);
    });

    it('returns false by default when not configured', function () {
        config()->set('cloudfront-cookies.enabled', null);

        expect(Config::isEnabled())->toBe(false);
    });
});

describe('Config getGuard', function () {
    it('returns null when not configured', function () {
        expect(Config::getGuard())->toBeNull();
    });

    it('returns configured guard', function () {
        config()->set('cloudfront-cookies.guard', 'api');

        expect(Config::getGuard())->toBe('api');
    });
});
