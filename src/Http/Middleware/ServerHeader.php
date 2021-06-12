<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;

/**
 * Class ServerHeader
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class ServerHeader
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $version = env('APP_VERSION', 'dev');
        $name = env('APP_NAME', 'Strong Lumen');

        $response->header('Server', sprintf('%s (%s)', $name, $version));
        $response->header('X-Powered-By', sprintf('%s (%s)', $name, $version));

        return $response;
    }
}