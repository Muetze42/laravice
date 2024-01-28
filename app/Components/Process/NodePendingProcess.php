<?php

namespace App\Components\Process;

use Illuminate\Process\PendingProcess;
use Symfony\Component\Process\Process;

class NodePendingProcess extends PendingProcess
{
    /**
     * Get a Symfony Process instance from the current pending command.
     */
    protected function toSymfonyProcess(array|string|null $command): Process
    {
        $command = $command ?? $this->command;
        $command = is_array($command) ? array_merge([config('process.commands.node')], $command) :
            config('process.commands.node') . ' ' . $command;

        return parent::toSymfonyProcess($command);
    }
}
