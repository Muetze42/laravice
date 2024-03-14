<?php

namespace App\Console\Commands;

use App\Support\Ability;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:list-grouped-abilities')]
class ListGroupedAbilitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-grouped-abilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available abilities grouped with parents';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info('Available abilities grouped with parents');

        foreach (Ability::grouped() as $ability) {
            $this->line($ability);
        }
    }
}
