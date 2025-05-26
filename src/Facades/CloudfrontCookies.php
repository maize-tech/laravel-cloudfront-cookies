<?php

namespace Maize\CloudfrontCookies\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Maize\CloudfrontCookies\CloudfrontCookies
 */
class CloudfrontCookies extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Maize\CloudfrontCookies\CloudfrontCookies::class;
    }
}
