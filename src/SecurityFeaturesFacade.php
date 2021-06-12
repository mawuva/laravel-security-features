<?php

namespace Mawuekom\LaravelSecurityFeatures;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mawuekom\LaravelSecurityFeatures\Skeleton\SkeletonClass
 */
class LaravelSecurityFeaturesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-security-features';
    }
}
