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
        if (! Config::isEnabled()) {
            return $next($request);
        }

        $guards = Config::getGuards();
        $isAuthenticated = collect($guards)
            ->contains(fn (string $guard) => (
                auth($guard)->check()
            ));

        if ($isAuthenticated) {
            CloudfrontCookies::queue();
        }

        return $next($request);
    }
}
