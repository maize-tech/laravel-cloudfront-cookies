<?php

namespace Maize\CloudfrontCookies\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Maize\CloudfrontCookies\Facades\CloudfrontCookies;

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
        // if (! app()->environment('production')) {
        //     return $next($request);
        // }

        // if (! auth()?->check()) { // TODO
        //     return $next($request);
        // }

        // check Middleware SignCloudfrontCookies

        CloudfrontCookies::queue();

        return $next($request);
    }
}
