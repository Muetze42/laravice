<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Storage as Facade;

class MediaStorage extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'filesystem.media';
    }
}
