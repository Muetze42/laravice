<?php

namespace App\Support\Facades;

use App\Components\Process\NodeFactory;
use Illuminate\Support\Facades\Process as Facade;

class NodeProcess extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return NodeFactory::class;
    }
}
