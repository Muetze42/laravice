<?php

namespace App\Services\Pdf;

use App\Services\AbstractService;

class LaravelPdf extends AbstractService
{
    /**
     * Determine the required packages for this service.
     */
    public static function requiredPackages(): array|string
    {
        return 'spatie/laravel-pdf';
    }
}
