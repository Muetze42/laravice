<?php

namespace App\Console\Commands\LaraVice;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
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
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(): void
    {
        parent::handle();

        $name = substr($this->getNameInput(), 0, -strlen('Service'));

        $this->call('make:controller', [
            'name' => $name . 'Controller',
            '--invokable' => true,
        ]);

        $file = base_path('routes/api.php');
        $contents = trim(file_get_contents($file));

        if (
            str_contains($contents, 'App\\Http\\Controllers\\' . $name . 'Controller;') ||
            str_contains($contents, 'App\\Services\\' . $name . 'Service;')
        ) {
            return;
        }

        $contents = str_replace(
            'use App\\Http\\Controllers\\WhoamiController;',
            "use App\\Http\\Controllers\\WhoamiController;\nuse App\\Http\\Controllers\\{$name}Controller;\n" .
            "use App\\Services\\{$name}Service;",
            $contents
        );

        $route = implode(
            '/',
            array_map(fn ($val) => Str::snake($val, '-'), explode('\\', $name))
        );

        $contents .= "\n" . 'if (' . class_basename($name) . 'Service::active()) {';
        $contents .= "\n\t" . 'Route::get(\'' . $route . '\', ' . class_basename($name) . 'Controller::class);';
        $contents .= "\n}";

        file_put_contents($file, str_replace("\t", '    ', $contents) . "\n");
    }

    /**
     * Get the desired class name from the input.
     */
    protected function getNameInput(): string
    {
        $name = trim($this->argument('name'));

        if (!str_ends_with($name, 'Service')) {
            $name .= 'Service';
        }

        return $name;
    }

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
