<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:config')]
class ConfigMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:config {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crate a LaraVice config YAML file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sourceFile = base_path('laravice.config.yaml.example');
        $targetFile = base_path('laravice.config.yaml');

        if (file_exists($targetFile) && !$this->option('force')) {
            $this->components->info(basename($targetFile) . ' already exists.');

            return;
        }

        file_put_contents(
            $targetFile,
            file_get_contents($sourceFile)
        );

        $this->components->info('The `' . basename($targetFile) . '` file created successfully.');
    }
}
