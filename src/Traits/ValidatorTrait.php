<?php

namespace Mawuekom\SecurityFeatures\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Trait ValidatorTrait
 * 
 * @package Mawuekom\SecurityFeatures\Traits
 */
trait ValidatorTrait
{
    /**
     * Set validation exception message
     * 
     * @param string $message
     * 
     * @throws ValidationException
     */
    private function throwValidationExceptionMessage($message)
    {
        $validator = Validator::make([], []);

        throw new ValidationException($validator, response()->json([
            'error' => $message,
        ], 422));
    }
}
