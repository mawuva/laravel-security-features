<?php

namespace Mawuekom\SecurityFeatures;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mawuekom\SecurityFeatures\Skeleton\SkeletonClass
 */
class SecurityFeaturesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'security-features';
    }
}
