<?php

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Maize\CloudfrontCookies\Facades\CloudfrontCookies;
use Maize\CloudfrontCookies\Listeners\ClearCloudfrontCookiesOnLogout;

beforeEach(function () {
    config()->set('cloudfront-cookies.cookie_domain', '.example.com');
});

describe('CloudfrontCookies clear', function () {
    it('queues cookies with empty values and negative expiration', function () {
        $queueCalls = 0;

        Cookie::shouldReceive('queue')
            ->times(3)
            ->andReturnUsing(function (...$args) use (&$queueCalls) {
                $queueCalls++;

                return null;
            });

        CloudfrontCookies::clear();

        expect($queueCalls)->toBe(3);
    });
});

describe('ClearCloudfrontCookiesOnLogout listener', function () {
    it('calls clear method on logout event', function () {
        CloudfrontCookies::shouldReceive('clear')
            ->once();

        $listener = new ClearCloudfrontCookiesOnLogout;
        $event = new Logout('web', (object) ['id' => 1]);

        $listener->handle($event);
    });

    it('is registered for logout event', function () {
        Event::fake();

        Event::assertListening(
            Logout::class,
            ClearCloudfrontCookiesOnLogout::class
        );
    });
});
