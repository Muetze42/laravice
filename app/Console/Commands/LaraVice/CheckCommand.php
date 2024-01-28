<?php

namespace App\Console\Commands\LaraVice;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'laravice:check')]
class CheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravice:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command LaraVice requirements';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $extensions = ['imagick', 'gd'];

        foreach ($extensions as $extension) {
            extension_loaded($extension) ? $this->components->info('Extension `' . $extension . '` found') :
                $this->components->error('Extension `' . $extension . '` not found');
        }
    }
}
