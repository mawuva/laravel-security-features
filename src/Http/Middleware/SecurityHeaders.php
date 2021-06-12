<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;

/**
 * Class SecurityHeaders
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class SecurityHeaders
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

        $response->header('X-Permitted-Cross-Domain-Policies', 'none');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'DENY');
        $response->header('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}