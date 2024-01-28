<?php

namespace App\Console\Commands;

use App\Console\Commands\LaraVice\ServiceMakeCommand as Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:service')]
class ServiceMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alias for laravice:make:service';
}
