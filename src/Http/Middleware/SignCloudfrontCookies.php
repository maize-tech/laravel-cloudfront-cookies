<?php

namespace App\Http\Middleware;

use Aws\CloudFront\CloudFrontClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CloudFrontCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (\App::environment('production')) {

            $cookies = $this->signACookiePolicy();

            $host = parse_url(config('app.url'))['host'];

            foreach ($cookies as $key => $value) {
                Cookie::queue($key, $value, 60 * 24 * 30, '/', ".{$host}", true);
            }
        }

        return $next($request);
    }

    public function signACookiePolicy()
    {
        $resourceKey = config('services.cloudfront.resource_key');

        $expires = time() + 3000;

        $privateKey = storage_path('private_key.pem');

        $keyPairId = config('services.cloudfront.key_id');

        $cloudFrontClient = new CloudFrontClient([
            'version' => '2014-11-06',
            'region' => 'us-east-1',
        ]);

        $policy = '{"Statement":[{"Resource":"'.$resourceKey.'","Condition":{"DateLessThan":{"AWS:EpochTime":'.$expires.'}}}]}';

        return $cloudFrontClient->getSignedCookie([
            'private_key' => $privateKey,
            'expires' => $expires,
            'key_pair_id' => $keyPairId,
            'policy' => $policy,
        ]);
    }
}
