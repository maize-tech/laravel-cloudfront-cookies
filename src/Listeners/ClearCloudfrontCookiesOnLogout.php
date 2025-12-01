<?php

namespace Maize\CloudfrontCookies\Listeners;

use Illuminate\Auth\Events\Logout;
use Maize\CloudfrontCookies\Facades\CloudfrontCookies;
use Maize\CloudfrontCookies\Support\Config;

class ClearCloudfrontCookiesOnLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if (Config::isEnabled()) {
            CloudfrontCookies::clear();
        }
    }
}
