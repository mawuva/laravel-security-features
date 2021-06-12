<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;

/**
 * Class NoCache
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class NoCache
{
    /**
     * Destroy all caches.
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

        $response->header('Cache-Control', 'no-cache, must-revalidate');

        return $response;
    }
}
