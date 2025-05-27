<?php

namespace Maize\CloudfrontCookies\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array make()
 * @method static void queue()
 * 
 * @see \Maize\CloudfrontCookies\CloudfrontCookies
 */
class CloudfrontCookies extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Maize\CloudfrontCookies\CloudfrontCookies::class;
    }
}
