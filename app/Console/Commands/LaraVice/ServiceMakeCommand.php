<?php

namespace App\Console\Commands\LaraVice;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'laravice:make:service')]
class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravice:make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new LaraVice Service';

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return base_path('stubs/laravice/service.stub');
    }
}
