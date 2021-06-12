<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;
use Mawuekom\SecurityFeatures\Traits\ValidatorTrait;

/**
 * Class AppIDMiddleware
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class AppIDMiddleware
{
    use ValidatorTrait;

    /**
     * Ensures that there is an App header present, and that is
     * matches the env setting.
     *
     * Set APP_ID in your .env
     * Request with `App: your-key-here`
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $appId = env('APP_ID');

        // Ensure that the requesting app is legit
        if (!is_null($appId) && ($appId === $request->header('App'))) {
            return $next($request);
        }

        $this ->throwValidationExceptionMessage('There was a problem validating the request.');
    }
}