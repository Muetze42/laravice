<?php

namespace App\Console\Commands;

use App\Support\Facades\TempStorage;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:storage:clear')]
class StorageClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:storage:clear';

    /**
     * The console command description.
     */
    protected $description = 'Remove temporary files from storage';

    /**
     * Files that are not to be deleted.
     */
    protected array $protectedFiles = ['.gitignore'];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = TempStorage::allFiles();
        foreach ($files as $file) {
            if (in_array(basename($file), $this->protectedFiles)) {
                continue;
            }
            if (TempStorage::lastModified($file) > now()->subHour()->timestamp) {
                continue;
            }
            TempStorage::delete($file);
        }
    }
}
