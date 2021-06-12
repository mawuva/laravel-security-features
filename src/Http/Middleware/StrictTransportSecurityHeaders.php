<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;

/**
 * Class StrictTransportSecurityHeaders
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class StrictTransportSecurityHeaders
{
    /**
     * Some common security headers.
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

        $response->header('Strict-Transport-Security', 'max-age=7776000; includeSubDomains');

        return $response;
    }
}