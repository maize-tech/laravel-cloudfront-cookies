<?php

namespace Maize\CloudfrontCookies\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Maize\CloudfrontCookies\Facades\CloudfrontCookies;
use Maize\CloudfrontCookies\Support\Config;

class SignCloudfrontCookies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if CloudFront cookies are enabled
        if (! Config::isEnabled()) {
            return $next($request);
        }

        // Only set cookies for authenticated users
        $guard = Config::getGuard();
        if (auth($guard)->check()) {
            CloudfrontCookies::queue();
        }

        return $next($request);
    }
}
