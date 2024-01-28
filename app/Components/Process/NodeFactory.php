<?php

namespace App\Components\Process;

use Illuminate\Process\Factory;

class NodeFactory extends Factory
{
    /**
     * Create a new pending process associated with this factory.
     */
    public function newPendingProcess(): NodePendingProcess
    {
        return (new NodePendingProcess($this))->withFakeHandlers($this->fakeHandlers);
    }
}
