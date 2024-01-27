<?php

namespace App\Services;

use Illuminate\Support\Arr;

abstract class AbstractService
{
    /**
     * Determine the required packages for this service.
     */
    abstract public static function requiredPackages(): array|string;

    /**
     * Determine if the service be available the web service.
     */
    public static function active(): bool
    {
        return !count(Arr::where(
            (array) static::requiredPackages(),
            fn ($requirement) => config('laravice.packages.' . $requirement) === false
        ));
    }
}
